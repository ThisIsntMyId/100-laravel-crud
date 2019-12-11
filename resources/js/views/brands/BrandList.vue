<template>
  <div class="app-container">
    <el-row :gutter="20" style="display: flex; align-items: center;">
      <el-col :span="10">
        <h1>Brands</h1>
      </el-col>
      <el-col :span="14" style="display: flex; justify-content: flex-end; align-items: center">
        <el-input
          v-model="navigation.search"
          placeholder="Search anything here"
          prefix-icon="el-icon-search"
          style="width: 300px; margin: 0 10px;"
          @keydown.enter.native="handleGlobalSearch"
        />
        <FilterPannel
          style="margin: 0 10px"
          @set-filter="handleFilterVals"
          @reset-filter="getTableData({})"
          :filter-pannel-obj="filterPannelObj"
        />
        <Import @import-success="handleImportSucces" @import-error="handleImportError" />
        <Export
          :all-data="allData"
          :current-data="tableData"
          :selected-data="selected"
          :header="['Id', 'Section Code', 'Language', 'Title', 'Content', 'Image', 'Image Mobile', 'Link', 'Button Link',]"
          :fields="['id', 'section_code', 'language', 'title', 'content', 'image', 'image_mobile', 'link', 'btn_link',]"
          file-name="BlocksData"
        />
        <el-button
          type="info"
          icon="el-icon-delete"
          @click="$refs['table'].clearSelection()"
        >Clear Selection</el-button>
        <el-button type="danger" icon="el-icon-delete" @click="handleMultipleDelete">Delete Selected</el-button>
      </el-col>
    </el-row>
    <el-row>
      <el-table
        ref="table"
        v-loading="loading.tableData"
        :data="tableData"
        border
        :row-key="getRowKeys"
        @sort-change="handleSortChange"
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" label="Selection" reserve-selection />
        <el-table-column label="Actions" width="200">
          <template slot-scope="scope">
            <div style="display: flex; justify-content: space-around;">
              <el-button
                icon="el-icon-view"
                type="primary"
                circle
                @click="$router.push(`/brands/view/${scope.row.id}`)"
              />
              <el-button
                icon="el-icon-edit"
                type="success"
                circle
                @click="$router.push(`/brands/edit/${scope.row.id}`)"
              />
              <el-button
                icon="el-icon-delete"
                type="danger"
                circle
                @click="handleDeleteClick(scope.row.id)"
              />
            </div>
          </template>
        </el-table-column>
        <el-table-column
          v-for="field in fieldsToShow"
          :key="field"
          :prop="field"
          :label="field.replace('_',' ')"
          sortable="custom"
        >
          <template slot-scope="scope">
            <div class="cell">{{ getLangValues(scope.row[field]) }}</div>
          </template>
        </el-table-column>
        <el-table-column label="icon" prop="icon">
          <template slot-scope="scope">
            <img :src="scope.row.icon" width="100px" height="100px" />
          </template>
        </el-table-column>
      </el-table>
    </el-row>
    <el-row>
      <pagination
        style="padding: 0;"
        :total="paginationData.total"
        :page.sync="paginationData.current_page"
        :limit.sync="paginationData.per_page"
        @pagination="handlePagination"
      />
    </el-row>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import FilterPannel from '@/components/FilterPannel';
import Export from './components/Export'; // ? not needed
import Import from './components/Import';
import axios from 'axios';
import Resource from '@/api/resource';
const BrandResource = new Resource('brands');

export default {
  name: 'BrandList',
  components: {
    Pagination,
    FilterPannel,
    Export,
    Import,
  },
  data() {
    return {
      language: 'en',
      tableData: [],
      fieldsToShow: ['name', 'slug', 'description', 'visits', 'offers_count'],
      paginationData: {
        current_page: 0,
        last_page: 0,
        per_page: 0,
        total: 0,
      },
      // currentSort: {
      //   field: '',
      //   order: 'asc',
      // },
      navigation: {
        page: 1,
        limit: 10,
        sort: '',
        'sort-order': 'asc',
        filters: '',
        search: '',
      },
      // searchQuery: '', // ? for search
      filters: '',
      selected: [], // ? for selection
      loading: {
        tableData: false,
      },
      allData: [],
      filterPannelObj: {
        section_code: {
          default: [],
          type: 'Select',
          label: 'Section Code',
          src: 'section_codes',
          multiple: true,
        },
        language: {
          default: [],
          type: 'CheckBox',
          label: 'Language',
          src: [
            { value: 'en', label: 'English' },
            { value: 'ar', label: 'Arabic' },
            { value: 'mr', label: 'Marathi' },
          ],
        },
        title: {
          default: '',
          type: 'Input',
          label: 'Title',
        },
        content: {
          default: '',
          type: 'Input',
          label: 'Content',
        },
        bool: {
          default: false,
          type: 'Boolean',
          label: 'Boolean Value',
        },
        activity: {
          default: '',
          type: 'Radio',
          label: 'Activity',
          src: [
            { value: 'd', label: 'Dance' },
            { value: 'r', label: 'Read' },
            { value: 'p', label: 'Play' },
          ],
        },
        price: {
          default: [100, 300],
          type: 'Range',
          label: 'Price',
          max: 500,
          min: 0,
        },
        rating: {
          default: 0,
          type: 'Rate',
          label: 'Rate Us',
        },
        taxes: {
          default: 0,
          type: 'Input',
          label: 'Taxes',
          default: '',
        },
      },
    };
  },
  watch: {
    async 'navigation.search'(newVal, oldVal) {
      await this.handleGlobalSearch();
    },
  },
  async created() {
    await this.getTableData({});
    this.allData = await BrandResource.list({ limit: -1 });
    const SectionResource = new Resource('sections');
    this.filterPannelObj.section_code.src = (await SectionResource.list({})).map(item => item.code);
  },
  methods: {
    async getTableData(query) {
      this.loading.tableData = true;
      const responseData = await BrandResource.list(query);
      this.tableData = responseData.data;
      this.paginationData = this.pick(
        ['current_page', 'last_page', 'per_page', 'total'],
        responseData
      );
      Object.keys(this.paginationData).forEach(
        key => (this.paginationData[key] = parseInt(this.paginationData[key]))
      );
      this.loading.tableData = false;
    },
    // utils
    pick(propsArr, srcObj) {
      return Object.keys(srcObj).reduce((obj, k) => {
        if (propsArr.includes(k)) {
          obj[k] = srcObj[k];
        }
        return obj;
      }, {});
    },
    getLangValues(str) {
      if (/\{.*\}/.test(str)) {
        return JSON.parse(str)[this.$store.state.app.language];
      } else {
        return str;
      }
    },
    // Sort
    async handleSortChange(change) {
      this.navigation.sort = change.prop;
      if (change.order === 'ascending') {
        this.navigation['sort-order'] = 'asc';
      } else if (change.order === 'descending') {
        this.navigation['sort-order'] = 'desc';
      }
      await this.getTableData(this.navigation);
    },
    // Pagination
    async handlePagination(obj) {
      // obj page obj containing {page: ..., limit: ...}
      this.navigation.page = obj.page;
      this.navigation.limit = obj.limit;
      await this.getTableData(this.navigation);
    },
    // Global Search
    async handleGlobalSearch() {
      await this.getTableData(this.navigation);
    },
    // ? Skipped for now
    // Filters
    async handleFilterVals(filterparams) {
      this.navigation.filters = JSON.stringify(filterparams.filters);
      console.log("TCL: handleFilterVals -> this.navigation.filters", this.navigation.filters)
      this.navigation.sort = filterparams.sort.field;
      this.navigation.sort = 'name';
      this.navigation['sort-order'] = filterparams.sort.asc ? 'asc' : 'desc';
      await this.getTableData(this.navigation);
    },
    async handleDeleteClick(id) {
      BrandResource.destroy(id)
        .then(res => {
          this.$message.success('Brand Deleted Successfully');
          this.getTableData({ page: this.paginationData.current_page });
        })
        .error(err => {
          this.$message.error(err);
        });
    },
    // Selection methods
    handleSelectionChange(selection) {
      this.selected = selection;
    },
    getRowKeys(row) {
      return row.id;
    },
    // Multiple Delete
    handleMultipleDelete() {
      axios
        .delete(
          `/api/brands/delete-multiple?ids=${this.selected
            .map(item => item.id)
            .join(',')}`
        )
        .then(async () => {
          this.$message.success('Records deleted successfully');
          await this.getTableData({ page: this.paginationData.current_page });
          if (this.tableData.length === 0) {
            await this.getTableData({
              page: this.paginationData.current_page,
            });
          }
          this.$refs.table.clearSelection();
        })
        .catch();
    },
    // Import Events
    handleImportSucces() {
      this.$message.success('New Data Imported');
      this.getTableData({});
    },
    handleImportError(err) {
      this.$message.error('There were some errors. CHK console');
      console.log(err);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>

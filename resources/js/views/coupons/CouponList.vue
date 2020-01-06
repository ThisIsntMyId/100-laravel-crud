<template>
  <div class="app-container">
    <el-row :gutter="20" style="display: flex; align-items: center;">
      <el-col :span="10">
        <h1 style="text-transform: capatilize;">{{ resourceName }}</h1>
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
          :filter-pannel-obj="filterPannelObj"
          @set-filter="handleFilterVals"
          @reset-filter="getTableData({})"
        />
        <Import
          :url="`/api/${resourceName}/upload`"
          @import-success="handleImportSucces"
          @import-error="handleImportError"
        />
        <Export :url="`/api/${resourceName}/export`" :selected-ids="selected.map(el => el.id)" />
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
                @click="$router.push(`/${resourceName}/view/${scope.row.id}`)"
              />
              <el-button
                :key="scope.row.id + 'switch'"
                :icon="scope.row.edited ? 'el-icon-upload' : 'el-icon-edit'"
                :type="scope.row.edited ? 'info' : 'success'"
                circle
                @click="scope.row.edited ? handleSaveClick(scope.row) : $router.push(`/${resourceName}/edit/${scope.row.id}`)"
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
          :key="field.name"
          :prop="field.name"
          :label="field.name.replace('_',' ')"
          sortable="custom"
        >
          <template slot-scope="scope">
            <div
              v-if="field.type=='multilangtext'"
              class="cell"
            >{{ JSON.parse(scope.row[field.name])[$store.state.app.language] }}</div>
            <div v-if="field.type=='text'" class="cell">{{ scope.row[field.name] }}</div>
            <el-tag v-if="field.type=='tag'">{{ scope.row.type }}</el-tag>
            <img v-if="field.type=='image'" :src="scope.row.icon" width="100px" height="100px" />
            <div v-if="field.type=='oneFrom'" class="cell">
              <async-tags
                :is-single="true"
                :url="field.url"
                :ids="scope.row[field.name]"
                :required-attr="field.attrName"
                :is-multi-lang="true"
              ></async-tags>
            </div>
            <div v-if="field.type=='manyFrom'" class="cell">
              <async-tags
                :is-single="false"
                :url="field.url"
                :ids="scope.row[field.name]"
                :required-attr="field.attrName"
                :is-multi-lang="true"
              ></async-tags>
            </div>
            <div v-if="field.type=='boolean'" class="cell">
              <t-switch
                :external-value="scope.row[field.name]"
                @switchchanged="event => handleInlineChange(event, scope.row.id, field)"
              />
            </div>
            <div v-if="field.type=='select'" class="cell">
              <t-select
                :external-value="scope.row[field.name]"
                :options="field.options"
                @selectchanged="event => handleInlineChange(event, scope.row.id, field)"
              />
            </div>
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
const resourceName = 'coupons';
const ResourceApi = new Resource(resourceName);
import TSwitch from './components/TSwitch';
import TSelect from './components/TSelect';
import AsyncTags from './components/AsyncTags';

export default {
  name: 'CategoryList',
  components: {
    Pagination,
    FilterPannel,
    Export,
    Import,
    TSwitch,
    TSelect,
    AsyncTags,
  },
  data() {
    return {
      resourceName: resourceName,
      language: 'en',
      tableData: [],
      fieldData: {},
      fieldsToShow: [
        { name: 'title', type: 'multilangtext' },
        // { name: 'description', type: 'multilangtext' },
        { name: 'code', type: 'text' },
        // { name: 'expiry_date', type: 'text' },
        { name: 'type', type: 'tag' },
        {
          name: 'store_id',
          type: 'oneFrom',
          url: '/api/stores/',
          attrName: 'name',
          multilang: true,
        },
        {
          name: 'tags',
          type: 'manyFrom',
          url: '/api/tags?idsarr=',
          attrName: 'name',
          multilang: true,
        },
        {
          name: 'brands',
          type: 'manyFrom',
          url: '/api/brands?idsarr=',
          attrName: 'name',
          multilang: true,
        },
        { name: 'is_featured', type: 'boolean', prerender: true },
        {
          name: 'status',
          type: 'select',
          options: ['publish', 'draft', 'trash'],
        },
      ],
      paginationData: {
        current_page: 0,
        last_page: 0,
        per_page: 0,
        total: 0,
      },
      navigation: {
        page: 1,
        limit: 10,
        sort: '',
        'sort-order': 'asc',
        filters: '',
        search: '',
      },
      filters: '',
      selected: [], // ? for selection
      loading: {
        tableData: false,
      },
      allData: [],
      filterPannelObj: {
        title: {
          default: '',
          type: 'Input',
          label: 'Title',
        },
        description: {
          default: '',
          type: 'Input',
          label: 'Description',
        },
        promo_text: {
          default: '',
          type: 'Input',
          label: 'Promo Text',
        },
        type: {
          default: [],
          type: 'CheckBox',
          label: 'Type',
          src: [
            { value: 'coupon', label: 'Coupon' },
            { value: 'offer', label: 'Offer' },
          ],
        },
        code: {
          default: '',
          type: 'Input',
          label: 'Code',
        },
        store_id: {
          default: [],
          type: 'AsyncSelect',
          label: 'Stores',
          src: '/api/stores?name',
          optionLabel: 'name',
          optionValue: 'id',
          multiple: true,
          multilang: true,
        },
        brands: {
          default: [],
          type: 'AsyncSelect',
          label: 'Brands',
          src: '/api/brands?name',
          optionLabel: 'name',
          optionValue: 'id',
          multiple: true,
          multilang: true,
        },
        tags: {
          default: [],
          type: 'AsyncSelect',
          label: 'Tags',
          src: '/api/tags?mname',
          optionLabel: 'name',
          optionValue: 'id',
          multiple: true,
          multilang: true,
        },
        cats: {
          default: [],
          type: 'AsyncSelect',
          label: 'Categories',
          src: '/api/categories?name',
          optionLabel: 'name',
          optionValue: 'id',
          multiple: true,
          multilang: true,
        },
        date: {
          default: '',
          type: 'DateTime',
          label: 'Date',
        }
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
    // this.allData = await ResourceApi.list({ limit: -1 });

    // // to fill filter dialog selects
    // // To get brands
    // this.filterPannelObj.brands.src = (await axios.get(
    //   `/api/brands?limit=-1`
    // )).data.map(({ name, id }) => ({
    //   label: JSON.parse(name)[this.$store.state.app.language],
    //   value: id,
    // }));
    // // To get tags
    // this.filterPannelObj.tags.src = (await axios.get(
    //   `/api/tags?limit=-1`
    // )).data.map(({ name, id }) => ({
    //   label: JSON.parse(name)[this.$store.state.app.language],
    //   value: id,
    // }));
    // // To get categories
    // this.filterPannelObj.cats.src = (await axios.get(
    //   `/api/categories?limit=-1`
    // )).data.map(({ name, id }) => ({
    //   label: JSON.parse(name)[this.$store.state.app.language],
    //   value: id,
    // }));
    // // To get stores
    // this.filterPannelObj.store_id.src = (await axios.get(
    //   `/api/stores?limit=-1`
    // )).data.map(({ name, id }) => ({
    //   label: JSON.parse(name)[this.$store.state.app.language],
    //   value: id,
    // }));
  },
  methods: {
    handleInlineChange(event, id, field) {
      const element = this.tableData.filter(data => data.id === id)[0];
      element[field.name] = event;
      this.$set(element, 'edited', true);
    },
    handleSaveClick(editedData, field) {
      alert('from save clik');
      console.log(editedData);
      // this.tableData.filter(data => data.id === editedData.id)[0]
      // I dont want to refetch the store ids, tags ids and brand ids
      this.$set(
        this.tableData.filter(data => data.id === editedData.id)[0],
        'edited',
        false
      );
      alert('edited');
    },
    async getTableData(query) {
      this.loading.tableData = true;
      const responseData = await ResourceApi.list(query);
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
      console.log('TCL: handleFilterVals -> filterparams', filterparams);
      this.navigation.filters = JSON.stringify(filterparams.filters);
      this.navigation.sort = filterparams.sort.field;
      this.navigation.sort = 'name';
      this.navigation['sort-order'] = filterparams.sort.asc ? 'asc' : 'desc';
      await this.getTableData(this.navigation);
    },
    async handleDeleteClick(id) {
      ResourceApi.destroy(id)
        .then(res => {
          this.$message.success('Delete Successfully');
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
          `/api/${this.resourceName}/delete-multiple?ids=${this.selected
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

<template>
  <div class="app-container">
    <el-row :gutter="20" style="display: flex; align-items: center;">
      <el-col :span="10">
        <h1>Blocks</h1>
      </el-col>
      <el-col :span="14" style="display: flex; justify-content: flex-end; align-items: center">
        <el-input
          v-model="searchQuery"
          placeholder="Search anything here"
          prefix-icon="el-icon-search"
          style="width: 300px; margin: 0 10px;"
          @keydown.enter.native="handleGlobalSearch"
        />
        <BlockFilter style="margin: 0 10px" @filter-params="handleFilterVals" />
        <Import @import-success="handleImportSucces" @import-error="handleImportError" />
        <Export
          :all-data="allData"
          :current-data="tableData"
          :selected-data="selectedBlocks"
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
                @click="$router.push(`/blocks/view/${scope.row.id}`)"
              />
              <el-button
                icon="el-icon-edit"
                type="success"
                circle
                @click="$router.push(`/blocks/edit/${scope.row.id}`)"
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
        <el-table-column prop="section_code" label="Section Code" width="200px" sortable="custom" />
        <el-table-column prop="language" label="Language" width="130px" sortable="custom" />
        <el-table-column prop="title" label="Title" sortable="custom" />
        <el-table-column prop="content" label="Content" width="500px" sortable="custom" />
        <!-- <el-table-column prop="image" label="Image" sortable="custom"/> -->
        <!-- <el-table-column prop="image_mobile" label="Image Mobile" sortable="custom"/> -->
        <el-table-column prop="link" label="Link" sortable="custom" />
        <el-table-column prop="btn_title" label="Btn Title" sortable="custom" />
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
import BlockFilter from './components/BlockFilter';
import Export from './components/Export';
import Import from './components/Import';
import axios from 'axios';
import Resource from '@/api/resource';
const BlockResource = new Resource('blocks');

export default {
  name: 'BlockList',
  components: {
    Pagination,
    BlockFilter,
    Export,
    Import,
  },
  data() {
    return {
      language: 'en',
      tableData: [],
      paginationData: {
        current_page: 0,
        last_page: 0,
        per_page: 0,
        total: 0,
      },
      currentSort: {
        field: '',
        order: 'asc',
      },
      searchQuery: '', // ? for search
      filters: '',
      selectedBlocks: [], // ? for selection
      loading: {
        tableData: false,
      },
      allData: [],
    };
  },
  watch: {
    async searchQuery(newVal, oldVal) {
      await this.handleGlobalSearch();
    },
  },
  async created() {
    await this.getBlocksData({});
    this.allData = await BlockResource.list({ limit: -1 });
    console.log('TCL: created -> this.allData', this.allData);
  },
  methods: {
    async getBlocksData(query) {
      this.loading.tableData = true;
      const responseData = await BlockResource.list(query);
      this.tableData = responseData.data;
      console.log('TCL: getBlocksData -> this.tableData', this.tableData);
      this.paginationData = this.pick(
        ['current_page', 'last_page', 'per_page', 'total'],
        responseData
      );
      Object.keys(this.paginationData).forEach(
        key => (this.paginationData[key] = parseInt(this.paginationData[key]))
      );
      this.loading.tableData = false;
    },
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
      this.currentSort.field = change.prop;
      if (change.order === 'ascending') {
        this.currentSort.order = 'asc';
      } else if (change.order === 'descending') {
        this.currentSort.order = 'desc';
      }
      await this.getBlocksData({
        filters: this.filters,
        search: this.searchQuery,
        page: this.paginationData.current_page,
        limit: this.paginationData.limit,
        sort: this.currentSort.field,
        'sort-order': this.currentSort.order,
      });
    },
    // Pagination
    async handlePagination(obj) {
      // obj page obj containing {page: ..., limit: ...}
      await this.getBlocksData({
        filters: this.filters,
        search: this.searchQuery,
        page: obj.page,
        limit: obj.limit,
        sort: this.currentSort.field,
        'sort-order': this.currentSort.order,
      });
    },
    // Global Search
    async handleGlobalSearch() {
      await this.getBlocksData({ search: this.searchQuery });
    },
    // Filters
    async handleFilterVals(filterparams) {
      this.filters = JSON.stringify(filterparams.filters);
      await this.getBlocksData({
        filters: this.filters,
        sort: filterparams.sort.field,
        'sort-order': filterparams.sort.asc ? 'asc' : 'desc',
      });
    },
    async handleDeleteClick(id) {
      BlockResource.destroy(id)
        .then(res => {
          this.$message.success('Block Deleted Successfully');
          this.getBlocksData({ page: this.paginationData.current_page });
        })
        .error(err => {
          this.$message.error(err);
        });
    },
    // Selection methods
    handleSelectionChange(selection) {
      this.selectedBlocks = selection;
    },
    clearSelected() {
      this.$refs['cardsTable'].clearSelection();
    },
    getRowKeys(row) {
      return row.id;
    },
    // Multiple Delete
    handleMultipleDelete() {
      axios
        .delete(
          `/api/blocks/delete-multiple?ids=${this.selectedBlocks
            .map(block => block.id)
            .join(',')}`
        )
        .then(async() => {
          this.$message.success('Records deleted successfully');
          await this.getBlocksData({ page: this.paginationData.current_page });
          if (this.tableData.length === 0) {
            await this.getBlocksData({
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
      this.getBlocksData({});
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

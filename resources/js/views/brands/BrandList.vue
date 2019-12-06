<template>
  <div class="app-container">
    <el-row :gutter="20" style="display: flex; align-items: center;">
      <el-col :span="10">
        <h1>Brands</h1>
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
      <!-- <el-table
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
        <el-table-column prop="image" label="Image" sortable="custom"/>
        <el-table-column prop="image_mobile" label="Image Mobile" sortable="custom"/>
        <el-table-column prop="link" label="Link" sortable="custom" />
        <el-table-column prop="btn_title" label="Btn Title" sortable="custom" />
      </el-table> -->
    </el-row>
    <el-row>
      <!-- <pagination
        style="padding: 0;"
        :total="paginationData.total"
        :page.sync="paginationData.current_page"
        :limit.sync="paginationData.per_page"
        @pagination="handlePagination"
      /> -->
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'BrandList',
};
</script>

<style>
</style>

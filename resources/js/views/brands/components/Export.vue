<template>
  <el-dropdown
    v-loading="loading.exportLoader"
    class="filter-item"
    style="margin-right: 10px; margin-left: 10px;"
    @command="handleExport"
  >
    <span class="el-dropdown-link">
      <el-button type="primary" icon="el-icon-download">
        Export
        <i class="el-icon-caret-bottom" />
      </el-button>
    </span>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item command="all-excel">Export all to excel</el-dropdown-item>
      <el-dropdown-item command="current-excel">Export current page to excel</el-dropdown-item>
      <el-dropdown-item command="selected-excel">Export selected to excel</el-dropdown-item>
      <el-dropdown-item command="all-csv" divided>Export all to csv</el-dropdown-item>
      <el-dropdown-item command="current-csv">Export current page to csv</el-dropdown-item>
      <el-dropdown-item command="selected-csv">Export selected to csv</el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import { exportWithFieldsToExcel, exportCSVFile } from '../src/exports';
export default {
  props: {
    allData: {
      type: Array,
      required: true,
    },
    currentData: {
      type: Array,
      required: true,
    },
    selectedData: {
      type: Array,
      required: true,
    },
    header: {
      type: Array,
      required: true,
    },
    fields: {
      type: Array,
      required: true,
    },
    fileName: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: {
        exportLoader: false,
      },
    };
  },
  methods: {
    async handleExport(command) {
      // ! Data provided here is not filtered for csv, i.e. ',' are not removed
      this.loading.exportLoader = true;
      const dataAmt = command.split('-')[0];
      const fileType = command.split('-')[1];
      let data = {};
      if (dataAmt === 'all') {
        data = this.allData;
      } else if (dataAmt === 'current') {
        data = this.currentData;
      } else {
        data = this.selectedData;
      }
      if (fileType === 'excel') {
        exportWithFieldsToExcel(this.header, this.fields, data, this.fileName);
      } else {
        exportCSVFile(this.header, this.fields, data, this.fileName);
      }
      this.loading.exportLoader = false;
    },
  },
};
</script>

<style>
</style>

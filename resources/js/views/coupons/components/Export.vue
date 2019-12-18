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
      <el-dropdown-item command="all-xlsx">Export all to excel</el-dropdown-item>
      <el-dropdown-item command="current-xlsx">Export current page to excel</el-dropdown-item>
      <el-dropdown-item command="selected-xlsx">Export selected to excel</el-dropdown-item>
      <el-dropdown-item command="all-csv" divided>Export all to csv</el-dropdown-item>
      <el-dropdown-item command="current-csv">Export current page to csv</el-dropdown-item>
      <el-dropdown-item command="selected-csv">Export selected to csv</el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import axios from 'axios';
export default {
  props: {
    url: {
      type: String,
      required: true,
    },
    selectedIds: {
      type: Array,
      default: () => [],
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
      this.loading.exportLoader = true;
      const dataAmt = command.split('-')[0];
      const fileType = command.split('-')[1];
      if (dataAmt === 'selected') {
        axios({
          url: `${
            this.url
          }?amt=${dataAmt}&type=${fileType}&ids=${this.selectedIds.join(',')}`,
          method: 'GET',
          responseType: 'blob', // important
        }).then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', `Brands.${fileType}`);
          document.body.appendChild(link);
          link.click();
        });
      } else {
        axios({
          url: `${
            this.url
          }?amt=${dataAmt}&type=${fileType}&ids=${this.selectedIds.join(',')}`,
          method: 'GET',
          responseType: 'blob', // important
        }).then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', `Brands.${fileType}`);
          document.body.appendChild(link);
          link.click();
        });
      }
      this.loading.exportLoader = false;
    },
  },
};
</script>

<style>
</style>

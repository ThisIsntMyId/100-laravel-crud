<template>
  <div>
    <el-button type="primary" icon="el-icon-upload" @click="uploadDialogVisible = true">Import</el-button>
    <el-dialog title="Import From Excel" :visible.sync="uploadDialogVisible" width="30%">
      <span>Enter excel file</span>
      <el-upload
        ref="upload"
        action="/api/blocks/upload-excel"
        name="file"
        :file-list="fileList"
        :on-change="handleUploadChange"
        :before-upload="handleBeforeUpload"
        :on-success="handleSuccess"
        :on-error="handleError"
        accept=".csv, .xlsx"
        :auto-upload="false"
      >
        <el-button slot="trigger" size="small" type="primary">select file</el-button>
        <el-button
          style="margin-left: 10px;"
          size="small"
          type="success"
          @click="submitUpload"
        >upload to server</el-button>
        <div slot="tip" class="el-upload__tip">must be a valid csv or excel file</div>
      </el-upload>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="uploadDialogVisible = false">Done</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      uploadDialogVisible: false,
      fileList: [],
    };
  },
  watch: {
    uploadDialogVisible(newVal, oldVal) {
      if (newVal === false) {
        this.fileList = [];
      }
    },
  },
  methods: {
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleUploadChange(file, fileList) {
      this.fileList = fileList.slice(-1);
    },
    handleBeforeUpload(file) {
      // Contains mime types for csv and excel files
      const allowedMime = [
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
        'application/vnd.ms-excel',
        'application/msexcel',
        'application/x-msexcel',
        'application/x-ms-excel',
        'application/x-excel',
        'application/x-dos_ms_excel',
        'application/xls',
        'application/x-xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      ];
      if (allowedMime.includes(file.type)) {
        return true;
      } else {
        this.$message.error(
          'You can only upload CSV or Excel files. No other file types are allowed'
        );
        this.fileList.pop(file);
      }
    },
    handleSuccess(response, file, fileList) {
      this.$emit('import-success');
      this.uploadDialogVisible = false;
    },
    handleError(err, file, fileList) {
      this.$emit('import-error', err);
    },
  },
};
</script>

<style>
</style>

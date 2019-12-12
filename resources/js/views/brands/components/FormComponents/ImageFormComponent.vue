<template>
  <el-form-item :label="label || (prop.charAt(0).toUpperCase() + prop.slice(1))" :prop="prop">
    <el-tooltip
      class="item"
      effect="dark"
      :content="tooltipMsg || (prop.charAt(0).toUpperCase() + prop.slice(1))"
      placement="right"
      :tabindex="-1"
    >
      <i class="el-icon-info" />
    </el-tooltip>
    <!-- <el-input :value="fieldValue" @input="$emit('update:fieldValue', $event)" /> -->
    <el-upload
      list-type="picture-card"
      :on-preview="handlePictureCardPreview"
      :on-remove="handleRemove"
      :auto-upload="false"
      :file-list="myFiles"
      :on-change="handleUploadChange"
    >
      <i class="el-icon-plus"></i>
    </el-upload>
    <el-button @click="$emit('update:fieldValue', myFiles)"> Go </el-button>
    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt />
    </el-dialog>
  </el-form-item>
</template>

<script>
export default {
  name: 'ImageFormComponent',
  props: {
    label: {
      type: String,
      default: '',
    },
    prop: {
      type: String,
      required: true,
    },
    tooltipMsg: {
      type: String,
      default: '',
    },
    fieldValue: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      myFiles: [],
    };
  },
  created() {
    this.myFiles = fieldValue;
  },
  methods: {
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleUploadChange(file, fileList) {
      this.myFiles = fileList;
    },
  },
  watch: {
    fieldValue(newValue, oldValue) {
      this.myFiles = newValue;
    },
  },
};
</script>

<style>
</style>

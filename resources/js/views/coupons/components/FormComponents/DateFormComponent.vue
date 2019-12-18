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
    <el-date-picker
      :value="fieldValue"
      @input="$emit('update:fieldValue', $event)"
      :type="pickerType"
      :placeholder="placeholder"
      :value-format="valueFormat"
      :format="displayFormat"
    ></el-date-picker>
  </el-form-item>
</template>

<script>
export default {
  name: 'DateFormComponent',
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
      type: String,
      required: true,
    },
    isTextarea: {
      type: Boolean,
      default: false,
    },
    pickerType: {
      type: String,
      default: 'date',
    },
    placeholder: {
      type: String,
      default: 'Pick A Day',
    },
    valueFormat: {
      type: String,
      default: 'yyyy-MM-dd',
    },
    displayFormat: {
      type: String,
      default: 'dd-MM-yyyy',
    },
  },
};
</script>

<style>
</style>

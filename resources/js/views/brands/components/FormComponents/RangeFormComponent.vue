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
    <el-slider
      :value="fieldValue"
      @input="$emit('update:fieldValue', $event)"
      range
      :min="range[0]"
      :max="range[1]"
      show-input-controls
    ></el-slider>
  </el-form-item>
</template>

<script>
export default {
  name: 'RangeFormComponent',
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
    range: {
      type: Array,
      required: true,
    },
    fieldWidth: {
      type: String,
      default: '300px',
    },
  },
};
</script>

<style>
</style>

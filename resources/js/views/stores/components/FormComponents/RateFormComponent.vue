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
    <el-rate :value="fieldValue" :max="max" @change="$emit('update:fieldValue', $event)" />
  </el-form-item>
</template>

<script>
export default {
  name: 'RateFormComponent',
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
      type: Boolean,
      required: true,
    },
    max: {
      type: Number,
      default: 5,
    },
  },
};
</script>

<style>
</style>

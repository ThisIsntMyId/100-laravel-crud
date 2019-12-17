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
    <el-switch
      :value="fieldValue"
      :active-value="trueValue"
      :inactive-text="falseValue"
      @change="$emit('update:fieldValue', $event)"
    />
  </el-form-item>
</template>

<script>
export default {
  name: 'BooleanFormComponent',
  props: {
    label: {
      type: String,
      default: '',
    },
    prop: {
      type: [String, Number, Boolean],
      required: true,
    },
    tooltipMsg: {
      type: String,
      default: '',
    },
    fieldValue: {
      type: [String, Number, Boolean],
      required: true,
    },
    trueValue: {
      type: [String, Number, Boolean],
      default: 1,
    },
    falseValue: {
      type: [String, Number, Boolean],
      default: 0,
    },
  },
};
</script>

<style>
</style>

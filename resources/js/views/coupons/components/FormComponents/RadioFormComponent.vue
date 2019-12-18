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
    <el-radio-group :value="fieldValue">
      <el-radio v-for="item in src" :key="item.value" :label="item.value" @change="$emit('update:fieldValue', item.value)">{{ item.label }}</el-radio>
    </el-radio-group>
  </el-form-item>
</template>

<script>
export default {
  name: 'RadioFormComponent',
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
    src: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>
</style>

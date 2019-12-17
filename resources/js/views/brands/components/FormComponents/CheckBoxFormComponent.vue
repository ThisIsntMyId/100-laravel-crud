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
    <el-checkbox-group :value="fieldValue">
      <el-checkbox
        v-for="item in src"
        :key="item.value"
        :label="item.value"
        @change="(event) => {event ? $emit('update:fieldValue', [...fieldValue, item.value]) : $emit('update:fieldValue', [...fieldValue.filter(el => el!==item.value)]);}"
      >{{ item.label }}</el-checkbox>
    </el-checkbox-group>
  </el-form-item>
</template>

<script>
export default {
  name: 'CheckBoxFormComponent',
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
    src: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>
</style>

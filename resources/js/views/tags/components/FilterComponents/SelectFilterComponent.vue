<template>
  <el-row style="margin-top: 15px;">
    <el-col :span="8">
      <strong style="font-size: 1.5rem">{{ label }}</strong>
    </el-col>
    <el-col :span="12">
      <el-select
        :value="paramValue"
        @change="$emit('update:paramValue', $event)"
        :placeholder="`Select ${label.toLowerCase()}`"
        :multiple="multiple"
        collapse-tags
        clearable
        style="width: 300px;"
      >
        <el-option v-for="item in src" :key="item" :label="item" :value="item" />
      </el-select>
    </el-col>
    <el-col :span="4">
      <div>
        <el-button
          :type="sortField === paramName ? 'success' : 'info'"
          :icon="sortField === paramName && sortAsc ? 'el-icon-sort-up' : 'el-icon-sort-down'"
          size="mini"
          circle
          @click="$emit('handle-sort-change', paramName)"
        />
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'SelectFilterComponent',
  props: {
    label: {
      type: String,
      required: true,
    },
    paramName: {
      type: String,
      required: true,
    },
    paramValue: {
      type: Array,
      required: true,
    },
    sortField: {
      type: String,
      required: true,
    },
    sortAsc: {
      type: Boolean,
      required: true,
    },
    src: {
      type: Array,
      required: true,
    },
    multiple: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style>
</style>

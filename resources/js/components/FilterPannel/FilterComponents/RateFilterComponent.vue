<template>
  <el-row style="margin-top: 15px;">
    <el-col :span="8">
      <strong style="font-size: 1.5rem">{{ label }}</strong>
    </el-col>
    <el-col :span="12">
      <el-rate :value="paramValue" :max="max" @change="$emit('update:paramValue', $event)" />
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
  name: 'RateFilterComponent',
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
      type: Number,
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
    max: {
      type: Number,
      default: 5,
    },
  },
};
</script>

<style>
</style>

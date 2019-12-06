<template>
  <el-row style="margin-top: 15px;">
    <el-col :span="8">
      <strong style="font-size: 1.5rem">{{ label }}</strong>
    </el-col>
    <el-col :span="12">
      <slot :paramValue="paramValue">
        <el-input
          :placeholder="label.toLowerCase()"
          style="width: 300px;"
          :value="paramValue"
          @input="$emit('update:paramValue', $event)"
        />
      </slot>
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
  name: 'FilterField',
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
      type: [String, Array, Number],
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
  },
};
</script>

<style>
</style>

<template>
  <el-row style="margin-top: 15px;">
    <el-col :span="8">
      <strong style="font-size: 1.5rem">{{ label }}</strong>
    </el-col>
    <el-col :span="12">
      <el-checkbox-group :value="paramValue">
        <el-checkbox
          v-for="item in src"
          :key="item.value"
          :label="item.value"
          @change="(event) => {event ? $emit('update:paramValue', [...paramValue, item.value]) : $emit('update:paramValue', [...paramValue.filter(el => el!==item.value)]);}"
        >{{item.label}}</el-checkbox>
      </el-checkbox-group>
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
  name: 'CheckBoxFilterComponent',
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
    }
  },
  methods: {
    handleChange(event) {
      console.log('TCL: handleChange -> event', event);
      this.$emit('update:paramValue', event);
    },
  },
};
</script>

<style>
</style>

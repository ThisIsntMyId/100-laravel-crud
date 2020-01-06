<template>
  <el-row style="margin-top: 15px;">
    <el-col :span="8">
      <strong style="font-size: 1.5rem">{{ label }}</strong>
    </el-col>
    <el-col :span="12">
      <el-select
        :value="paramValue"
        :placeholder="`Select ${label.toLowerCase()}`"
        :multiple="multiple"
        :collapse-tags="collapseTags"
        clearable
        filterable
        remote
        :remote-method="remoteMethod"
        :loading="loading"
        loading-text="Loading Data ..."
        style="width: 300px;"
        @change="$emit('update:paramValue', $event)"
      >
        <el-option
          v-for="item in options"
          :key="item[optionValue] || item"
          :label="multilang ? item[optionLabel][$store.state.app.language] || item[$store.state.app.language] : item[optionLabel] || item"
          :value="item[optionValue] || item"
        />
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
import axios from 'axios';
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
      type: String,
      required: true,
    },
    optionLabel: {
      type: String,
      default: 'label',
    },
    optionValue: {
      type: String,
      default: 'value',
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    multilang: {
      type: Boolean,
      default: false,
    },
    collapseTags: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      options: [],
      loading: false,
    };
  },
  methods: {
    async remoteMethod(query) {
      this.loading = true;
      if (query.length >= 3) {
        this.options = (await axios.get(`${this.src}=${query}`)).data;
        if (this.multilang) {
          this.options.forEach(option => {
            console.log(
              'TCL: remoteMethod -> option[this.optionLabel]',
              option[this.optionLabel]
            );
            option[this.optionLabel] = JSON.parse(option[this.optionLabel]);
          });
        }
      } else {
        this.options = [];
      }
      this.loading = false;
    },
  },
};
</script>

<style>
</style>

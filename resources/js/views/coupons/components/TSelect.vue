<template>
  <el-select
    v-model="localdata"
    placeholder="Select"
    @change="handleChange"
  >
    <el-option v-for="item in options" :key="item" :label="item" :value="item"></el-option>
  </el-select>
</template>

<script>
export default {
  name: 'TSwitch',
  props: {
    externalValue: {
      type: [String, Number],
      required: true,
    },
    options: {
      type: Array,
      required: true,
    }
  },
  data() {
    return {
      localdata: false,
    };
  },
  created() {
    this.localdata = this.externalValue;
  },
  methods: {
    handleChange(event) {
      // alert('from tselect');
      // alert(event)
      this.$emit('selectchanged', event);
    },
  },
};
</script>

<style>
</style>
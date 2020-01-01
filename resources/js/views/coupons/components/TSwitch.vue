<template>
  <el-switch v-model="localdata" :active-value="1" :inactive-value="0" @change="handleChange"></el-switch>
</template>

<script>
export default {
  name: 'TSwitch',
  props: {
    externalValue: {
      type: [String, Number, Boolean],
      required: true,
    },
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
        //   alert('from tswitch');
        //   alert(event)
          this.$emit('switchchanged', event);
      },
  }
};
</script>

<style>
</style>
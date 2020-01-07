<template>
  <div class="editable" contenteditable="true" @input="bhoooo">{{localdata}}</div>
</template>

<script>
export default {
  name: 'TEditText',
  props: {
    externalValue: {
      type: [String, Number],
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
    bhoooo(e) {
      this.$emit('textchanged', e.target.innerText);
    },
  },
};
</script>

<style scoped>
.editable{
    border: #dfe6ec 1px solid;
    /* cursor: pointer; */
}

.editable:focus {
  border: red 1px solid;
}
</style>
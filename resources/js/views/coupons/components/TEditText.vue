<template>
  <div class="editable" contenteditable="true" spellcheck="false" @input="contentChanged">{{localdata}}</div>
</template>

<script>
export default {
  name: 'TEditText',
  props: {
    externalValue: {
      required: true,
      validator: prop => typeof prop === 'string' || typeof prop === 'number' || prop === null
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
    // FIXME Change this name
    contentChanged(e) {
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
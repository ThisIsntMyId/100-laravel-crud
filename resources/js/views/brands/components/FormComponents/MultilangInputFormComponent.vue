// TODO: Work on default language

<template>
  <el-form-item :prop="prop">
    <el-row>
      <strong>
        {{label || (prop.charAt(0).toUpperCase() + prop.slice(1))}}
        <el-tooltip
          class="item"
          effect="dark"
          :content="tooltipMsg || (prop.charAt(0).toUpperCase() + prop.slice(1))"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
      </strong>
      <el-radio-group v-model="selectedLang" style="float: right;" size="small">
        <el-radio-button v-for="lang in langs" :key="lang" :label="lang">{{lang}}</el-radio-button>
      </el-radio-group>
    </el-row>
    <el-input v-model="inputData[selectedLang]" @change="handleInputUpdate" />
  </el-form-item>
</template>
                 
<script>
export default {
  name: 'MultilangInputFormComponent',
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
      type: String,
      required: true,
    },
    langs: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      selectedLang: this.$store.state.app.language,
      inputData: { en: '' },
    };
  },
  created() {
    this.inputData = JSON.parse(this.fieldValue);
  },
  methods: {
    handleInputUpdate() {
      const parsedData = JSON.stringify(this.inputData);
      this.$emit('update:fieldValue', parsedData);
    },
  },
  watch: {
    fieldValue(newVal, oldVal) {
      this.inputData = JSON.parse(newVal);
    },
  }
};
</script>

<style>
</style>

// TODO: Work on default language

<template>
  <el-form-item :prop="prop">
    <span slot="label">{{ label || (prop.charAt(0).toUpperCase() + prop.slice(1)) }}</span>
    <span>
      <el-tooltip
        class="item"
        effect="dark"
        :content="tooltipMsg || (prop.charAt(0).toUpperCase() + prop.slice(1))"
        placement="right"
        :tabindex="-1"
      >
        <i class="el-icon-info" />
      </el-tooltip>
    </span>
    <el-radio-group v-model="selectedLang" style="float: right;" size="small">
      <el-radio-button v-for="lang in langs" :key="lang" :label="lang">{{ lang }}</el-radio-button>
    </el-radio-group>
    <el-input
      v-model="inputData[selectedLang]"
      :type="isTextarea ? 'textarea' : 'input'"
      @change="handleInputUpdate"
    />
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
    isTextarea: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      selectedLang: this.$store.state.app.language,
      inputData: { en: '' },
    };
  },
  watch: {
    fieldValue(newVal, oldVal) {
      this.inputData = JSON.parse(newVal);
    },
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
};
</script>

<style>
</style>

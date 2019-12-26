<template>
  <div class="app-container">
    <h1>Hello this is a container for ajax select</h1>
    <p>you can see that the values are comming from backend for the select options</p>
    <el-row>
      <el-col :span="8">Stores</el-col>
      <el-col :span="16">
        <AjaxSelectComponent
          :get-selected-items="selectedItems"
          :get-searched-items="searchedItems"
          :multiple="mul"
          :drag="dr"
          option-label="name"
          option-value="id"
          :field-value.sync="wrapperValue"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import axios from 'axios';
import AjaxSelectComponent from './AjaxSelectComponent';
export default {
  components: {
    AjaxSelectComponent,
  },
  data() {
    return {
      // wrapperValue: '',
      // wrapperValue: [],
      // wrapperValue: 45502,
      wrapperValue: [45502, 31204, 45598, 9116],
      // mul: false,
      mul: true,
      dr: true,
      // dr: false,
    };
  },
  methods: {
    async selectedItems(value) {
      alert(typeof value);
      if (typeof value === 'string' || typeof value === 'number') {
        return (await axios.get(
          `/api/stores?ids=${this.wrapperValue}`
        )).data.map(({ name, id }) => ({
          name: (x => JSON.parse(x)[this.$store.state.app.language])(name),
          id: id,
        }));
      } else if (Array.isArray(value)) {
        return (await axios.get(
          `/api/stores?ids=${this.wrapperValue.join(',')}`
        )).data.map(({ name, id }) => ({
          name: (x => JSON.parse(x)[this.$store.state.app.language])(name),
          id: id,
        }));
      } else {
        return [];
      }
    },
    async searchedItems(searchQuery) {
      return (await axios.get(`/api/stores?name=${searchQuery}`)).data.map(
        ({ name, id }) => ({
          name: (x => JSON.parse(x)[this.$store.state.app.language])(name),
          id: id,
        })
      );
    },
  },
};
</script>
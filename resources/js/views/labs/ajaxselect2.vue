<template>
  <div class="app-container">
    <el-select
      v-model="value"
      multiple
      filterable
      remote
      :remote-method="remoteMethod"
      reserve-keyword
      placeholder="Select"
      style="width: 500px;"
    >
      <el-option v-for="item in options" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </el-select>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      options: [],
      value: [],
      // value: [45502, 31204, 45598, 9116],
    };
  },
  async created() {
    this.options = (await axios.get(
        `/api/stores?ids=${this.value.join(',')}`
      )).data.map(({ name, id }) => ({
        name: (x => JSON.parse(x)[this.$store.state.app.language])(name),
        id: id,
      }));
  },
  methods: {
    async remoteMethod(query) {
      if (query.length > 2) {
        this.options = (await axios.get(
          `/api/stores?name=${query}`
        )).data.map(({ name, id }) => ({
          name: (x => JSON.parse(x)[this.$store.state.app.language])(name),
          id: id,
        }));
      } else {
        this.options = [];
      }
    },
  },
};
</script>
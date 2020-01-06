<template>
  <div class="app-container">
    <h1>Ajax Select</h1>
    <el-select
      ref="ajaxselect"
      @onScroll.native="handleScroll"
      multiple
      filterable
      remote
      :remote-method="remoteMethod"
      v-model="value"
      placeholder="Select"
    >
      <el-option v-for="item in options1" :key="item.id" :label="item.name" :value="item"></el-option>
    </el-select>
    <el-select
      ref="ajaxselect2"
      @onScroll.native="handleScroll"
      multiple
      v-model="value2"
      placeholder="Select"
    >
      <el-option v-for="item in options2" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </el-select>
    <el-select multiple v-model="value3" value-key="id">
      <el-option v-for="fruit in option3" :key="fruit.id" :value="fruit" :label="fruit.name"></el-option>
    </el-select>
    <template v-for="(type) in ['primary', 'info', 'danger']"> <component :key="type" :is="randomeHtmlElement(option3[0].name, type)" @fromello="fromHello"></component> </template>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AjasSelect',
  data() {
    return {
      options1: [],
      options2: [],
      value: [1683, 42344, 1689, 1951, 2028, 29910, 1922],
      value2: [1732, 1689, 2028, 1951, 1922],
      value31: [1, 2],
      value3: [
        {
          id: 1,
          name: 'apple',
        },
        {
          id: 2,
          name: 'strawberry',
        },
      ],
      option3: [
        {
          id: 1,
          name: 'apple',
        },
        {
          id: 2,
          name: 'strawberry',
        },
        {
          id: 3,
          name: 'peach',
        },
        {
          id: 4,
          name: 'banana',
        },
      ],
    };
  },
  async created() {
    const data = (await axios.get(`/api/stores?limit=-1`)).data.map(
      ({ name, id }) => ({
        name: JSON.parse(name)[this.$store.state.app.language],
        id: id,
      })
    );
    this.options2 = data;
    if (this.value.length > 0) {
      this.options1 = (await axios.get(
        `/api/stores?ids=${this.value.join(',')}`
      )).data.map(({ name, id }) => ({
        name: JSON.parse(name)[this.$store.state.app.language],
        id: id,
      }));
    }
  },
  computed: {},
  watch: {
    value(newVal, oldVal) {
      this.options1 = [];
    },
  },
  methods: {
    fromHello(event) {
      alert(`event from new component => ${event}`);
    },
    randomeHtmlElement(name, type) {
      return {
        template: `<el-button type="${type}" @click='helloClicked'>Hello from computed ${name}</el-button>`,
        methods: {
          helloClicked() {
            alert(`from hello clicked => ${type}`);
            this.$emit('fromello', name.toUpperCase());
          },
        },
      };
    },
    async remoteMethod(input) {
      if (input.length > 0) {
        let existingData;
        if (this.value.length > 0) {
          existingData = (await axios.get(
            `/api/stores?ids=${this.value.join(',')}`
          )).data;
        } else {
          existingData = [];
        }

        const requestedData = (await axios.get(`/api/stores?name=${input}`))
          .data.data;

        this.options1 = [...new Set([...existingData, ...requestedData])].map(
          ({ name, id }) => ({
            name: JSON.parse(name)[this.$store.state.app.language],
            id: id,
          })
        );
        return;
      }
    },
  },
};
</script>

<style>
</style>

<template>
  <div class="app-container">
    <h1>New</h1>
    <div>
      <FileManagerInput showImg />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput showImg />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput recursive />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput recursive showImg />
    </div>
    <br />
  </div>
</template>

<script>
import FileManagerInput from './components/FileManagerInput.vue';
export default {
  name: 'New',
  components: { FileManagerInput },
  updated() {},
  data() {
    return {};
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
</style>

<template>
  <div class="app-container">
    <h1>New</h1>
    <div>
      <FileManagerInput :field-value.sync="value1" showImg />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput :field-value.sync="value2" />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput :field-value.sync="value3" showImg />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput :field-value.sync="value4" recursive />
    </div>
    <br />
    <br />
    <div>
      <FileManagerInput :field-value.sync="value5" recursive showImg />
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
    return {
      value1: '[]',
      // value1: "[\"/storage/download (2).jpg\"]",
      value2: '[]',
      // value2: "[\"/storage/megasiteDev_LV.sql.zip\",\"/storage/app.js\",\"/storage/composer.json\"]",
      value3: '[]',
      // value3: "[\"/storage/My Images 2/cat images/download (2).jpg\",\"/storage/My Images 2/cat images/download (3).jpg\",\"/storage/My Images 2/cat images/download (4).jpg\",\"/storage/My Images 2/cat images/images (1).jpg\"]",
      // value4: '[]',
      value4: "[\"/storage/app.js\",\"/storage/tag/icon/uOkDN1HAco9rqrKRnisF6bkOHQwcDFY4GEhRWfzq.png\"]",
      // value5: '[]',
      value5: "[\"/storage/screen shots/Screenshot_5.png\",\"/storage/screen shots/Screenshot_2.png\",\"/storage/other images/37392486-dbz-wallpapers.jpg\",\"/storage/other images/95327.jpg\",\"/storage/My Images 2/cat images/download (2).jpg\",\"/storage/My Images 2/cat images/download (4).jpg\"]",
    };
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
</style>

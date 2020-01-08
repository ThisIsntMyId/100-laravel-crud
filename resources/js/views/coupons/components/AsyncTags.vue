<template>
  <div>
    <div v-if="isSingle">
      <el-tag v-if="!errorMsg">{{isMultiLang ? tagsData[$store.state.app.language] : tagsData}}</el-tag>
      <el-tag v-else type="danger">{{errorMsg}}</el-tag>
    </div>
    <div v-else>
      <div v-if="!errorMsg">
        <el-tag
          style="margin: 5px"
          v-for="(item, index) in tagsData"
          :key="`${isMultiLang ? item[$store.state.app.language] : item}+${index}`"
        >{{isMultiLang ? item[$store.state.app.language] : item}}</el-tag>
      </div>
      <el-tag v-else type="danger">{{errorMsg}}</el-tag>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  // Will get names of fields from the ids provided
  name: 'AsyncTags',
  props: {
    isSingle: {
      type: Boolean,
      default: true,
    },
    isMultiLang: {
      type: Boolean,
      default: false,
    },
    url: {
      type: String,
      required: true,
    },
    ids: {
      required: true,
      validator: prop => typeof prop === 'string' || typeof prop === 'number' || prop === null
    },
    requiredAttr: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      tagsData: [],
      errorMsg: '',
    };
  },
  async created() {
    if (this.isSingle) {
      try {
        this.tagsData = (await axios.get(`${this.url}${this.ids}`)).data[
          this.requiredAttr
        ];
        if (this.isMultiLang) {
          this.tagsData = JSON.parse(this.tagsData);
        }
      } catch {
        this.errorMsg = 'No Data Available';
      }
    } else {
      try {
        this.tagsData = (await axios.get(`${this.url}${this.ids}`)).data.map(
          idata =>
            this.isMultiLang
              ? JSON.parse(idata[this.requiredAttr])
              : idata[this.requiredAttr]
        );
      } catch {
        this.errorMsg = 'No Data Available';
      }
    }
  },
};
</script>

<style scoped>
.el-tag{
  height: auto;
  white-space: pre-wrap;
}
</style>
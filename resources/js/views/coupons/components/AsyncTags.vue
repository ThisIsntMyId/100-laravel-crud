<template>
  <div>
    <div v-if="isSingle">
      <el-tag v-if="!errorData">{{isMultiLang ? tagsData[$store.state.app.language] : tagsData}}</el-tag>
      <el-tag v-else type="danger">{{errorData}}</el-tag>
    </div>
    <div v-else>
      <div v-if="!errorData">
        <el-tag
          style="margin: 5px"
          v-for="(item, index) in tagsData"
          :key="`${isMultiLang ? item[$store.state.app.language] : item}+${index}`"
        >{{isMultiLang ? item[$store.state.app.language] : item}}</el-tag>
      </div>
      <el-tag v-else type="danger">{{errorData}}</el-tag>
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
      type: [String, Number],
      required: true,
    },
    requiredAttr: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      tagsData: [],
      errorData: '',
      // FIXME: For data sometimes when its not available add no data available error
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
        this.errorData = 'No Data Available';
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
        this.errorData = 'No Data Available';
      }
    }
  },
};
</script>

<style>
</style>
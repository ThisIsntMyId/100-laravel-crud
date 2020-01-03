<template>
  <div>
    <div style="display: flex;">
      <div class="btn-container" v-if="!recursive">
        <el-button type="primary" @click="showFileManager = true">Choose File...</el-button>
      </div>
      <div class="tag-container" v-if="(selectedFiles.length > 0 || recursive) && !showImg">
        <el-tag type="success" v-if="recursive" @click="showFileManager = true">
          <i class="el-icon-plus" /> Add Files ...
        </el-tag>
        <el-tag
          :key="tag"
          v-for="tag in selectedFiles"
          closable
          @close="handleClose(tag)"
          @click="handleTagClick(tag)"
        >{{tag}}</el-tag>
      </div>
      <div class="imgs-container" v-if="(selectedFiles.length > 0 || recursive) && showImg">
        <div class="img-container" v-if="recursive">
          <img
            :src="`https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS11Kqs2MoeDQlJai0bc_Tju-yW_cOFYVwSf9JPP8DGv9H4GtiS&s`"
            @click="showFileManager = true"
          />
        </div>
        <div class="img-container" v-for="(img, index) in selectedFiles" :key="index">
          <img :src="`${img}`" :alt="`${img}`" />
          <span class="overlay">
            <i class="el-icon-close" @click="handleImageRemove(img)"></i>
          </span>
        </div>
      </div>
    </div>
    <el-dialog :visible.sync="showFileManager">
      <file-manager></file-manager>
      <span slot="footer" class="dialog-footer">
        <el-button id="doneBtn" @click="handleSelected">
          Done
          <i class="fas fa-check"></i>
        </el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'FileManagerInput',
  props: {
    recursive: {
      type: Boolean,
      default: false,
    },
    showImg: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      showFileManager: false,
      selectedFiles: [],
    };
  },
  methods: {
    handleSelected() {
      if (this.recursive) {
        this.selectedFiles = [
          ...this.selectedFiles,
          ...this.$store.state.fm.left.selected.files.map(
            file => '/storage/' + file
          ),
        ];
      } else {
        this.selectedFiles = this.$store.state.fm.left.selected.files.map(
          file => '/storage/' + file
        );
      }
      this.showFileManager = false;
    },
    handleClose(tag) {
      this.selectedFiles.splice(this.selectedFiles.indexOf(tag), 1);
    },
    handleImageRemove(img) {
      this.selectedFiles.splice(this.selectedFiles.indexOf(img), 1);
    },
    handleTagClick(tag) {
      alert(tag);
    },
  },
};
</script>

<style lang="scss" scoped>
#doneBtn {
  background: #5a6268;
  font-weight: 800;
  letter-spacing: 1.1px;
  text-align: center;
  vertical-align: middle;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.25rem;
  color: white;
}

.el-tag {
  margin: 5px;
  cursor: pointer;
}
.btn-container {
  display: flex;
  align-items: center;
  padding: 0 5px;
}

.tag-container,
.imgs-container {
  padding: 5px 15px;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
}

.img-container {
  height: 100px;
  width: 100px;
  margin: 10px;
  display: inline-block;
  position: relative;
  border-radius: 4px;
  padding: 1px;
  border: 1px solid #dcdfe6;
  cursor: pointer;

  & img {
    height: 100%;
    width: 100%;
    border-radius: 4px;
  }

  & .overlay {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
  }

  &:hover .overlay {
    display: block;
  }

  & .overlay i {
    color: white;
    font-size: 24px;
    position: absolute;
    top: 0;
    left: 100%;
    transform: translateX(-100%);
    cursor: pointer;
    padding: 5px;
  }
}
</style>
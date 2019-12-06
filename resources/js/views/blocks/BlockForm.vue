<template>
  <div class="app-container">
    <h1>Block Form {{ $route.params.id }}</h1>
    <el-form ref="form" v-loading="loading.blockData" :model="formData" :rules="validationRules">
      <el-form-item label="Section Code" prop="section_code">
        <el-tooltip
          class="item"
          effect="dark"
          content="Which section does this block belongs to"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-select v-model="formData.section_code" placeholder="Select">
          <el-option v-for="item in section_codes" :key="item" :label="item" :value="item" />
        </el-select>
      </el-form-item>
      <el-form-item label="Language" prop="language">
        <el-tooltip
          class="item"
          effect="dark"
          content="Language of the section"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-radio-group v-model="formData.language">
          <el-radio label="en">English</el-radio>
          <el-radio label="ar">Arabic</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="Title" prop="title">
        <el-tooltip
          class="item"
          effect="dark"
          content="Title of the section"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-input v-model="formData.title" />
      </el-form-item>
      <el-form-item label="Content" prop="content">
        <el-tooltip
          class="item"
          effect="dark"
          content="Content of the section"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-input v-model="formData.content" />
      </el-form-item>
      <el-form-item label="Image" prop="image">
        <el-tooltip
          class="item"
          effect="dark"
          content="Image of the section"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-input v-model="formData.image" />
      </el-form-item>
      <el-form-item label="Image Mobile" prop="image_mobile">
        <el-tooltip
          class="item"
          effect="dark"
          content="Mobile image of the section"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-input v-model="formData.image_mobile" />
      </el-form-item>
      <el-form-item label="Link" prop="link">
        <el-tooltip
          class="item"
          effect="dark"
          content="Button Link of the section"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-input v-model="formData.link" />
      </el-form-item>
      <el-form-item label="Button Title" prop="btn_title">
        <el-tooltip
          class="item"
          effect="dark"
          content="Title of the section button"
          placement="right"
          :tabindex="-1"
        >
          <i class="el-icon-info" />
        </el-tooltip>
        <el-input v-model="formData.btn_title" />
      </el-form-item>
      <el-form-item>
        <div style="display: flex; justify-content: start;">
          <el-button icon="el-icon-back" type="primary" circle @click="$router.push(`/blocks`)" />
          <el-button
            icon="el-icon-upload"
            type="success"
            circle
            @click="$route.params.id ? handleUpdate() : handleCreate()"
          />
          <el-button
            icon="el-icon-refresh-left"
            type="info"
            circle
            @click="$route.params.id ? getBlockData($route.params.id) : $refs['form'].resetFields()"
          />
          <el-button
            v-if="$route.params.id"
            icon="el-icon-delete"
            type="danger"
            circle
            @click="handleDeleteClick($route.params.id)"
          />
        </div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const BlockResource = new Resource('blocks');
const SectionResource = new Resource('sections');

export default {
  components: {},
  data() {
    return {
      formData: {
        section_code: '',
        language: 'en',
        title: '',
        content: '',
        image: '',
        image_mobile: '',
        link: '',
        btn_link: '',
      },
      validationRules: {
        section_code: [
          {
            required: true,
            message: 'Section Code is required',
            trigger: 'blur',
          },
        ],
        language: [
          {
            required: true,
            message: 'Language is required',
            trigger: 'blur',
          },
        ],
        title: [
          {
            required: true,
            message: 'Title is required',
            trigger: 'blur',
          },
        ],
      },
      section_codes: [],
      loading: {
        blockData: false,
      },
    };
  },
  async created() {
    // await this.getBlocksData({ page: 2 });
    await this.getSectionCodes();
    if (this.$route.params.id) {
      await this.getBlockData(this.$route.params.id);
    }
  },
  methods: {
    async getBlockData(id) {
      try {
        this.loading.blockData = true;
        const blockData = await BlockResource.get(id);
        this.formData = blockData;
        this.loading.blockData = false;
      } catch (e) {
        console.log(e);
        this.$router.push({ name: 'Page404' });
      }
    },
    async getSectionCodes() {
      this.section_codes = (await SectionResource.list({})).map(
        item => item.code
      );
    },
    async handleCreate() {
      // Todo: this stmt returns promise. Try to isolate it to one function
      this.$refs['form'].validate(valid => {
        if (valid) {
          BlockResource.store(this.formData)
            .then(res => {
              this.$message.success('Block Added Successfully');
              this.$refs['form'].resetFields();
              this.$router.push(`/blocks/view/${res.id}`);
            })
            .catch(res => {
              this.$message.error(res);
            });
        } else {
          this.$message.error('Please fill all the fields as per instructions');
        }
      });
    },
    async handleUpdate() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          const id = this.$route.params.id;
          BlockResource.update(id, this.formData)
            .then(res => {
              this.$message.success('Block Updated Successfully');
              this.getBlockData(id);
            })
            .catch(res => {
              this.$message.error(res);
            });
        } else {
          this.$message.error('Please fill all the fields as per instructions');
        }
      });
    },
    async handleDeleteClick(id) {
      BlockResource.destroy(id)
        .then(res => {
          this.$message.success('Block Deleted Successfully');
          this.$router.push(`/blocks`);
        })
        .error(err => {
          this.$message.error(err);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.el-tooltip.el-icon-info {
  position: relative;
  left: -8px;
}
</style>

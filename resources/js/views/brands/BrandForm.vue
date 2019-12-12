<template>
  <div class="app-container">
    <h1>Block Form {{ $route.params.id }}</h1>
    <el-form ref="form" v-loading="loading.blockData" :model="formData" :rules="validationRules">
      <SelectFormComponent
        label="Select Section Code"
        tooltip-msg="Code of the section"
        prop="section_code"
        :field-value.sync="formData.section_code"
        :src="section_codes"
        multiple
        draggable
        field-width="500px"
      />
      <RadioFormComponent
        label="Language"
        tooltip-msg="Language of the section"
        prop="language"
        :field-value.sync="formData.language"
        :src="[
            { value: 'en', label: 'English' },
            { value: 'ar', label: 'Arabic' },
            { value: 'mr', label: 'Marathi' },
          ]"
      />
      <CheckBoxFormComponent
        label="Activity"
        tooltip-msg="Activity of the section"
        prop="activity"
        :field-value.sync="formData.activity"
        :src="[
            { value: 'da', label: 'Dance' },
            { value: 're', label: 'Read' },
            { value: 'py', label: 'Play' },
          ]"
      />
      <MultilangInputFormComponent
        label="Multilang Input"
        tooltip-msg="Data in different languges"
        prop="multilangData"
        :field-value.sync="formData.multilangData"
        :langs="['en', 'ar', 'mr']"
      />
      <BooleanFormComponent
        label="Buy"
        tooltip-msg="Do you want to buy it?"
        prop="buyIt"
        :field-value.sync="formData.buyIt"
      />
      <RangeFormComponent
        label="Price"
        tooltip-msg="The price of the ..."
        prop="price"
        :range="[0,500]"
        :field-value.sync="formData.price"
      />
      <RateFormComponent
        label="Rate Us"
        tooltip-msg="How much do u like us?"
        prop="ratings"
        :field-value.sync="formData.ratings"
      />
      <ImageFormComponent prop="imageFile" :field-value.sync="formData.imageFile" />
      <InputFormComponent
        label="Title"
        tooltip-msg="Title of the section"
        prop="title"
        :field-value.sync="formData.title"
      />
      <InputFormComponent
        label="Content"
        tooltip-msg="Content of the section"
        prop="content"
        :field-value.sync="formData.content"
      />
      <InputFormComponent
        label="Image"
        tooltip-msg="Image of the section"
        prop="image"
        :field-value.sync="formData.image"
      />
      <InputFormComponent
        label="Image Mobile"
        tooltip-msg="Mobile image of the section"
        prop="image_mobile"
        :field-value.sync="formData.image_mobile"
      />
      <InputFormComponent
        label="Link"
        prop="link"
        tooltip-msg="Button Link of the section"
        :field-value.sync="formData.link"
      />
      <InputFormComponent
        label="Button Title"
        prop="btn_title"
        tooltip-msg="Title of the section button"
        :field-value.sync="formData.btn_title"
      />

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

import InputFormComponent from './components/FormComponents/InputFormComponent';
import RadioFormComponent from './components/FormComponents/RadioFormComponent';
import CheckBoxFormComponent from './components/FormComponents/CheckBoxFormComponent';
import SelectFormComponent from './components/FormComponents/SelectFormComponent';
import MultilangInputFormComponent from './components/FormComponents/MultilangInputFormComponent';
import BooleanFormComponent from './components/FormComponents/BooleanFormComponent';
import RangeFormComponent from './components/FormComponents/RangeFormComponent';
import RateFormComponent from './components/FormComponents/RateFormComponent';
import ImageFormComponent from './components/FormComponents/ImageFormComponent';

export default {
  components: {
    InputFormComponent,
    RadioFormComponent,
    CheckBoxFormComponent,
    SelectFormComponent,
    MultilangInputFormComponent,
    BooleanFormComponent,
    RangeFormComponent,
    RateFormComponent,
    ImageFormComponent,
  },
  data() {
    return {
      formData: {
        section_code: [],
        language: 'en',
        title: '',
        content: '',
        image: '',
        image_mobile: '',
        link: '',
        btn_link: '',
        activity: [],
        multilangData: '{"ar": "الصفحة الرئيسية", "en": "All Brands"}',
        buyIt: false,
        ratings: 3,
        price: [200, 300],
        imageFile: [],
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

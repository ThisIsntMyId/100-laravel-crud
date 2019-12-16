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
import axios from 'axios';

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
      formData: {},
      formJson: {
        name: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Name',
          tooltip: 'your name in your language',
          langs: ['en', 'ar'],
          validation: [
            {
              required: true,
              trigger: 'blur',
              validator: (role, value, callback) => {
                !value || value === '{}'
                  ? callback(new Error('Please enter a name'))
                  : '';
              },
            },
          ],
        },
        slug: {
          type: 'Input',
          default: '',
          label: 'Slug',
          tooltip: 'The slug of page',
          validation: [
            {
              required: true,
              message: 'PLease enter a slug',
              trigger: 'blur',
            },
          ],
        },
        description: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Description',
          tooltip: 'your descripiton in your language',
          langs: ['en', 'ar'],
          isTextarea: true,
        },
        h1_tag: {
          type: 'Input',
          default: '',
          label: 'H1 Tag',
          tooltip: 'The h1 tag of page',
        },
        h2_tag: {
          type: 'Input',
          default: '',
          label: 'H2 Tag',
          tooltip: 'The h2 tag of page',
        },
        meta_title: {
          type: 'Input',
          default: '',
          label: 'Meta Title',
          tooltip: 'The meta title of page',
        },
        icon: {
          type: 'Image',
          default: [],
          label: 'Icon',
          tooltipMsg: 'Icon of the brand',
          onlyOne: true,
        },
        headerImage: {
          type: 'Image',
          default: [],
          label: 'Header Image',
          tooltip: 'image fot the header',
        },
        recommendedStore: {
          type: 'Input',
          default: '',
          label: 'Recommended Stores',
          tooltip: 'recommended stores',
        },
        overridesStore: {
          type: 'Boolean',
          default: false,
          label: 'Override Store',
          validation: [
            {
              required: true,
              message: 'Please mention wether to override store or not',
              trigger: 'blur',
            },
          ],
        },
        visits: {
          type: 'Input',
          default: '0',
          label: 'Visits',
          validation: [
            {
              required: true,
              message: 'PLease enter a no. of visitors',
              trigger: 'blur',
            },
            {
              pattern: /^[\d]+$/,
              message: 'No of visits should only be numbers',
              trigger: 'blur',
            },
          ],
        },
        excludesSitemap: {
          type: 'Boolean',
          default: false,
          label: 'Exclude Sitemap',
          validation: [
            {
              required: true,
              message: 'Please mention wether to exclude sitemapr or not',
              trigger: 'blur',
            },
          ],
        },
        filters: {
          type: 'Input',
          default: '',
          label: 'Filterd',
          tooltip: 'What filters should apply here',
          isTextarea: true,
        },
        offerCount: {
          type: 'Input',
          default: '0',
          label: 'Offer Counts',
          validation: [
            {
              required: true,
              message: 'PLease enter no. of offers',
              trigger: 'blur',
            },
            {
              pattern: /^[\d]+$/,
              message: 'Offer count should only be numbers',
              trigger: 'blur',
            },
          ],
        },
      },
      validationRules: {},
      loading: {
        blockData: false,
      },
    };
  },
  async created() {
    // await this.getSectionCodes();
    if (this.$route.params.id) {
      await this.getBlockData(this.$route.params.id);
    }
    this.setFormDataObj();
    this.setValidationObj();
  },
  methods: {
    async getBlockData(id) {
      try {
        this.loading.blockData = true;
        const blockData = await BlockResource.get(id);
        this.formData = blockData;
        this.loading.blockData = false;
      } catch (e) {
        this.$router.push({ name: 'Page404' });
      }
    },
    async getSectionCodes() {
      this.formJson.section_code.src = (await SectionResource.list({})).map(
        item => item.code
      );
    },
    setFormDataObj() {
      this.formData = Object.entries(this.formJson).reduce((acc, obj) => {
        acc[obj[0]] = obj[1].default;
        return acc;
      }, {});
    },
    setValidationObj() {
      this.validationRules = Object.entries(this.formJson).reduce(
        (acc, obj) => {
          obj[1].validation && (acc[obj[0]] = obj[1].validation);
          return acc;
        },
        {}
      );
    },
    async handleCreate() {
      alert('before validation');
      // Todo: this stmt returns promise. Try to isolate it to one function
      this.$refs['form'].validate(valid => {
        alert('after validation');
        console.log(valid);
        if (valid) {
          alert('is valid');
          axios
            .post('/api/brands/test', this.formData)
            .then(res => {
              this.$message.success('Block Added Successfully');
              this.$refs['form'].resetFields();
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
          axios
            .post('/api/brands/test', this.formData)
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
    getFormComponent(h, componentName, componentDef) {
      return h(componentDef.type + 'FormComponent', {
        props: {
          label: componentDef.label,
          tooltipMsg: componentDef.tooltip,
          prop: componentName,
          fieldValue: this.formData[componentName],
          ...componentDef,
        },
        on: {
          'update:fieldValue': event => {
            this.formData[componentName] = event;
          },
        },
      });
    },
  },
  render(h) {
    return (
      <div class="app-container">
        <h1>Block Form {this.$route.params.id}</h1>
        <el-form
          ref="form"
          v-loading={this.loading.blockData}
          model={this.formData}
          rules={this.validationRules}
        >
          {Object.entries(this.formJson).map(componentObj =>
            this.getFormComponent(h, componentObj[0], componentObj[1])
          )}
          <el-form-item>
            <div style="display: flex; justify-content: start;">
              <el-button
                icon="el-icon-back"
                type="primary"
                circle
                onClick={() => this.$router.push(`/blocks`)}
              />
              <el-button
                icon="el-icon-upload"
                type="success"
                circle
                onClick={() =>
                  this.$route.params.id
                    ? this.handleUpdate()
                    : this.handleCreate()
                }
              />
              <el-button
                icon="el-icon-refresh-left"
                type="info"
                circle
                onClick={() =>
                  this.$route.params.id
                    ? this.getBlockData(this.$route.params.id)
                    : this.$refs['form'].resetFields()
                }
              />
              {this.$route.params.id ? (
                <el-button
                  icon="el-icon-delete"
                  type="danger"
                  circle
                  onClick={() => this.handleDeleteClick(this.$route.params.id)}
                />
              ) : (
                ''
              )}
            </div>
          </el-form-item>
        </el-form>
      </div>
    );
  },
};
</script>

<style lang="scss" scoped>
.el-tooltip.el-icon-info {
  position: relative;
  left: -8px;
}
</style>

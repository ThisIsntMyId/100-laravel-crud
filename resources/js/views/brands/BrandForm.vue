// TODO Make sure you have same formData attribute name as you get from api res[ponse]
<script>
import Resource from '@/api/resource';
const ResourseName = 'brands';
const ResourceApi = new Resource(ResourseName);

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
                  : callback();
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
            {
              pattern: /^[\S\-]+$/,
              message: 'No white spaces allowed',
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
          type: 'MultilangInput',
          default: '{}',
          label: 'H1 Tag',
          tooltip: 'The h1 tag of page',
          langs: ['en', 'ar'],
        },
        h2_tag: {
          type: 'MultilangInput',
          default: '{}',
          label: 'H2 Tag',
          tooltip: 'The h2 tag of page',
          langs: ['en', 'ar'],
        },
        meta_title: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Meta Title',
          tooltip: 'The meta title of page',
          langs: ['en', 'ar'],
        },
        meta_desc: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Meta Description',
          tooltip: 'The meta description',
          isTextarea: true,
          langs: ['en', 'ar'],
        },
        meta_kw: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Meta KW',
          tooltip: 'The kw of meta',
          langs: ['en', 'ar'],
        },
        icon: {
          type: 'Image',
          default: [],
          label: 'Icon',
          tooltipMsg: 'Icon of the brand',
          onlyOne: true,
        },
        header_image: {
          type: 'Image',
          default: [],
          label: 'Header Image',
          tooltip: 'image fot the header',
          onlyOne: true,
        },
        recom_store: {
          type: 'Input',
          default: '',
          label: 'Recommended Stores',
          tooltip: 'recommended stores',
        },
        override_stores: {
          type: 'Boolean',
          default: 0,
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
        exclude_sitemap: {
          type: 'Boolean',
          default: 0,
          label: 'Exclude Sitemap',
          validation: [
            {
              required: true,
              message: 'Please mention wether to exclude sitemapr or not',
              trigger: 'blur',
            },
          ],
        },
        filter: {
          type: 'Input',
          default: '',
          label: 'Filterd',
          tooltip: 'What filters should apply here',
          isTextarea: true,
        },
        offers_count: {
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
        resourceData: false,
      },
    };
  },
  async created() {
    this.setFormDataObj();
    this.setValidationObj();
    if (this.$route.params.id) {
      await this.getResourceData(this.$route.params.id);
    }
  },
  methods: {
    pick(propsArr, srcObj) {
      return Object.keys(srcObj).reduce((obj, k) => {
        if (propsArr.includes(k)) {
          obj[k] = srcObj[k];
        }
        return obj;
      }, {});
    },
    async getResourceData(id) {
      console.log('TCL: getResourceData -> this.formData', this.formData);
      try {
        this.loading.resourceData = true;
        const resourceData = await ResourceApi.get(id);
        // TODO Remember to preprocess data before this assingment
        this.formData = this.pick(Object.keys(this.formJson), resourceData);
        for (const item in this.formJson) {
          if (
            this.formJson[item].type === 'Image' &&
            this.formJson[item].onlyOne
          ) {
            this.formData[item] = [{ name: item, url: this.formData[item] }];
          } else {
            // TODO do something for array of image
          }
        }
        this.loading.resourceData = false;
      } catch (e) {
        this.$router.push({ name: 'Page404' });
      }
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
    // TODO Remember to rename accordingily
    generateDataToSubmit(sourceData) {
      const formdata = new FormData();
      for (const item in sourceData) {
        if (this.formJson[item].type === 'Image') {
          if (this.formJson[item].onlyOne) {
            formdata.append(item, sourceData[item][0].raw);
          } else {
            for (const file of sourceData[item]) {
              formdata.append(`${item}[]`, file.raw);
            }
          }
        } else {
          formdata.append(item, sourceData[item]);
        }
      }
      return formdata;
    },
    async handleCreate() {
      // Todo: this stmt returns promise. Try to isolate it to one function
      this.$refs['form'].validate(valid => {
        if (valid) {
          // TODO Remember to process the data for server before this
          const dataToSubmit = this.generateDataToSubmit(this.formData);
          axios
            .post(`/api/${ResourseName}`, dataToSubmit, {
              headers: {
                'Content-Type': `multipart/form-data; boundary=${dataToSubmit._boundary}`,
              },
            })
            .then(res => {
              this.$message.success('Added Successfully');
              this.setFormDataObj();
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
          // TODO Remember to process the data for server before this
          console.log('TCL: handleUpdate -> this.formData', this.formData);
          const dataToSubmit = this.generateDataToSubmit(this.formData);
          // due to laravel not handeling formdata with put request we need to spoof the request
          dataToSubmit.append('_method', 'put');
          axios
            .post(`/api/${ResourseName}/${id}`, dataToSubmit, {
              headers: {
                'Content-Type': `multipart/form-data; boundary=${dataToSubmit._boundary}`,
              },
            })
            .then(res => {
              this.$message.success('Updated Successfully');
              this.getResourceData(id);
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
      ResourceApi.destroy(id)
        .then(res => {
          this.$message.success('Deleted Successfully');
          this.$router.push(`/${ResourseName}`);
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
        <h1>{ResourseName.slice(0,1).toUpperCase() + ResourseName.slice(1,-1)} Form {this.$route.params.id ? '(Edit)' : ''}</h1>
        <el-form
          ref="form"
          v-loading={this.loading.resourceData}
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
                onClick={() => this.$router.push(`/${ResourseName}`)}
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
                    ? this.getResourceData(this.$route.params.id)
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

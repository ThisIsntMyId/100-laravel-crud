// TODO Make sure you have same formData attribute name as you get from api res[ponse]
<script>
import Resource from '@/api/resource';
const ResourseName = 'coupons';
const ResourceApi = new Resource(ResourseName);

import InputFormComponent from '@/components/FormComponents/InputFormComponent';
import RadioFormComponent from '@/components/FormComponents/RadioFormComponent';
import CheckBoxFormComponent from '@/components/FormComponents/CheckBoxFormComponent';
import SelectFormComponent from '@/components/FormComponents/SelectFormComponent';
import MultilangInputFormComponent from '@/components/FormComponents/MultilangInputFormComponent';
import BooleanFormComponent from '@/components/FormComponents/BooleanFormComponent';
import RangeFormComponent from '@/components/FormComponents/RangeFormComponent';
import RateFormComponent from '@/components/FormComponents/RateFormComponent';
import ImageFormComponent from '@/components/FormComponents/ImageFormComponent';
import DateFormComponent from '@/components/FormComponents/DateFormComponent';
import AjaxSelectFormComponent from '@/components/FormComponents/AjaxSelectFormComponent';
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
    AjaxSelectFormComponent,
    DateFormComponent,
  },
  data() {
    return {
      formData: {},
      formJson: {
        title: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Title',
          tooltip: 'Title of the coupon',
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
        store_id: {
          type: 'AjaxSelect',
          default: '',
          label: 'Ajax Store',
          tooltip: 'Ajax Store to which the coupon belongs',
          optionValue: 'id',
          optionLabel: 'name',
          initializeurl: '/api/stores?ids',
          searchUrl: '/api/stores?name',
          multilang: true,
          langs: ['en', 'ar'],
          // multiple: true,
          // draggable: true,
        },
        description: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Description',
          tooltip: 'Description about Store',
          langs: ['en', 'ar'],
          isTextarea: true,
        },
        promo_text: {
          type: 'MultilangInput',
          default: '{}',
          label: 'Promo Text',
          tooltip: 'Promo Text on coupon',
          langs: ['en', 'ar'],
        },
        type: {
          type: 'Radio',
          default: '',
          label: 'Type',
          tooltip: 'Type of the coupon',
          src: [
            { value: 'coupon', label: 'Coupon' },
            { value: 'offer', label: 'Offer' },
          ],
          validation: [
            {
              required: true,
              message: 'Field is required',
              trigger: 'blur',
            },
          ],
        },
        code: {
          type: 'Input',
          default: '',
          label: 'Code',
          tooltip: 'Code of the coupon',
          validation: [
            {
              required: true,
              message: 'Field is required',
              trigger: 'blur',
            },
          ],
        },
        link: {
          type: 'Input',
          default: '',
          label: 'Link',
          tooltip: 'Link of the coupon',
          validation: [
            {
              required: true,
              message: 'Field is required',
              trigger: 'blur',
            },
          ],
        },
        brands: {
          type: 'AjaxSelect',
          default: '',
          label: 'Ajax Brands',
          tooltip: 'Brands to which the coupon belongs',
          optionValue: 'id',
          optionLabel: 'name',
          initializeurl: '/api/brands?ids',
          searchUrl: '/api/brands?name',
          multilang: true,
          langs: ['en', 'ar'],
          multiple: true,
          draggable: true,
        },
        tags: {
          type: 'AjaxSelect',
          default: '',
          label: 'Ajax Tags',
          tooltip: 'Ajax Tags applicable to the coupon',
          optionValue: 'id',
          optionLabel: 'name',
          initializeurl: '/api/tags?mids',
          searchUrl: '/api/tags?mname',
          multilang: true,
          langs: ['en', 'ar'],
          multiple: true,
          draggable: true,
        },
        cats: {
          type: 'AjaxSelect',
          default: '',
          label: 'Ajax Categories',
          tooltip: 'Categories to which the coupon belongs',
          optionValue: 'id',
          optionLabel: 'name',
          initializeurl: '/api/categories?ids',
          searchUrl: '/api/categories?name',
          multilang: true,
          langs: ['en', 'ar'],
          multiple: true,
          draggable: true,
        },
        is_featured: {
          type: 'Boolean',
          default: 0,
          label: 'Is Featured',
        },
        created_mode: {
          type: 'Input',
          default: '',
          label: 'created_mode',
          tooltip: 'created_mode',
        }, 
        network_id: {
          type: 'Input',
          default: '',
          label: 'network_id',
          tooltip: 'network_id',
        },
        network_store_id: {
          type: 'Input',
          default: '',
          label: 'network_store_id',
          tooltip: 'network_store_id',
        },
        expiry_date: {
          type: 'Date',
          default: '',
          label: 'Expiry Date',
          tooltip: 'Expiry Date',
          displayFormat: 'dd-MM-yyyy',
        },
        status: {
          type: 'Radio',
          default: '',
          label: 'Status',
          tooltip: 'Status of cashback',
          src: [
            { value: 'draft', label: 'Draft' },
            { value: 'publish', label: 'Publish' },
            { value: 'trash', label: 'Trash' },
          ],
        },
        clicks: {
          type: 'Input',
          default: '',
          label: 'clicks',
          tooltip: 'clicks',
          validation: [
            {
              required: true,
              message: 'field is required',
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
  watch: {
    'this.$store.state.app.language'() {},
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
      try {
        this.loading.resourceData = true;
        const resourceData = await ResourceApi.get(id);
        // TODO Remember to preprocess data before this assingment
        this.formData = this.pick(Object.keys(this.formJson), resourceData);
        for (const item in this.formJson) {
          if (this.formData[item]) {
            switch (this.formJson[item].type) {
              case 'AjaxSelect':
              case 'Select': {
                if (this.formJson[item].multiple === true) {
                  this.formData[item] = JSON.parse(this.formData[item]);
                }
                break;
              }
              case 'Image': {
                if (this.formJson[item].onlyOne === true) {
                  this.formData[item] = [
                    { name: item, url: this.formData[item] },
                  ];
                } else {
                  // TODO for array of images
                }
                break;
              }
            }
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
        if (
          sourceData[item] &&
          this.formJson[item].type === 'Image' &&
          sourceData[item].length > 0
        ) {
          if (this.formJson[item].onlyOne) {
            formdata.append(item, sourceData[item][0].raw);
          } else {
            for (const file of sourceData[item]) {
              formdata.append(`${item}[]`, file.raw);
            }
          }
        } else if (
          (this.formJson[item].type === 'Select' || this.formJson[item].type === 'AjaxSelect') &&
          this.formJson[item].multiple
        ) {
          formdata.append(item, JSON.stringify(sourceData[item]));
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
        <h1>
          {ResourseName.slice(0, 1).toUpperCase() + ResourseName.slice(1, -1)}{' '}
          Form {this.$route.params.id ? '(Edit)' : ''}
        </h1>
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

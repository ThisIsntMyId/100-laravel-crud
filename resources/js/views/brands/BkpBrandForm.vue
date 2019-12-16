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
        // simple input
        name: {
          type: 'Input',
          label: 'Name',
          default: '',
          validation: [
            {
              required: true,
              message: 'Please name',
              trigger: 'blur',
            },
          ],
        },
        // boolean
        booleans: {
          type: 'Boolean',
          label: 'Booleans',
          default: false,
        },
        // checkbox
        activity: {
          type: 'CheckBox',
          default: [],
          label: 'Activity',
          tooltip: 'The core activity',
          validation: [
            { required: true, message: 'Chose atleast one', trigger: 'blur' },
          ],
          src: [
            { value: 'da', label: 'Dance' },
            { value: 're', label: 'Read' },
            { value: 'py', label: 'Play' },
          ],
        },
        // image
        profileImage: {
          type: 'Image',
          label: 'Profile Image',
          default: [],
          tooltipMsg: 'Your photo that is to be displayed on top',
          validation: [
            {
              required: true,
              message: 'upload an image',
              trigger: blur,
            },
          ],
        },
        // multiline input
        tagline: {
          type: 'MultilangInput',
          label: 'TagLine',
          default: '{}',
          langs: ['en', 'ar'],
          validation: [
            {
              required: true,
              trigger: 'blur',
              validator: (role, value, callback) => {
                !value || value === '{}'
                  ? callback(new Error('Please enter a tagline'))
                  : callback();
              },
            },
          ],
        },
        // radio
        language: {
          type: 'Radio',
          default: '',
          label: 'Language',
          tooltip: 'The Language of your page',
          src: [
            { value: 'en', label: 'English' },
            { value: 'ar', label: 'Arabic' },
            { value: 'mr', label: 'Marathi' },
          ],
          validation: [
            {
              required: true,
              message: 'Language is required',
              trigger: 'blur',
            },
          ],
        },
        // range
        priceRange: {
          type: 'Range',
          default: [100, 300],
          label: 'Price',
          tooltipMsg: 'Your budget',
          range: [0, 500],
          validation: [
            {
              trigger: 'blur',
              validator: (rule, value, callback) => {
                value[0] > 350 && value[1] <= 450
                  ? callback(new Error('You cant buy in this price range'))
                  : callback();
              },
            },
          ],
        },
        // Ratings
        ratings: {
          type: 'Rate',
          default: 0,
          label: 'Rate Us',
          tooltipMsg: 'How much do u like us?',
          validation: [
            {
              required: true,
            },
            {
              trigger: 'blur',
              validator: (rule, value, callback) => {
                value === 0
                  ? callback(
                      new Error(
                        ' Ratings are very important to us. So please rate us...'
                      )
                    )
                  : value < 2 && value > 0
                  ? callback(
                      new Error('You cannot rate us less then 2 stars now')
                    )
                  : callback();
              },
            },
          ],
        },
        // Select
        section_code: {
          type: 'Select',
          default: [],
          label: 'Section Code',
          tooltip: 'The section code',
          // src: ['argentina', 'brazil', 'peru', 'columbia', 'parague'],
          src: [
            { labeel: 'argentina', valuew: 'ar' },
            { labeel: 'brazil', valuew: 'br' },
            { labeel: 'peru', valuew: 'pr' },
            { labeel: 'columbia', valuew: 'co' },
            { labeel: 'parague', valuew: 'pg' },
          ],
          optionValue: 'valuew',
          optionLabel: 'labeel',
          multiple: true,
          draggable: true,
          validation: [
            {
              required: true,
              message: 'Section Code is required',
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

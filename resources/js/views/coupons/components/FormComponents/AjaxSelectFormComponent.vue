<script>
// hard coded search param
import ElDragSelect from '@/components/DragSelect';
import axios from 'axios';

export default {
  name: 'AjaxSelectFormComponent',
  components: { ElDragSelect },
  props: {
    label: {
      type: String,
      default: '',
    },
    prop: {
      type: String,
      required: true,
    },
    tooltipMsg: {
      type: String,
      default: '',
    },
    fieldValue: {
      type: [Array, String, Number],
      required: true,
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    optionValue: {
      type: String,
      default: 'value',
    },
    optionLabel: {
      type: String,
      default: 'label',
    },
    draggable: {
      type: Boolean,
      default: false,
    },
    fieldWidth: {
      type: String,
      default: '300px',
    },
    initializeurl: {
      type: String,
      required: true,
    },
    searchUrl: {
      type: String,
      required: true,
    },
    // Its been assumed that you have all corresponding language values of option label text in the json formate in returned data
    multilang: {
      type: Boolean,
      default: false,
    },
    langs: {
      type: Array,
      default: ['en'],
    },
    customMapFunction: {
      type: Function,
      default: null,
    },
    // async. if you want to retrieve data as per your terms. takes two args: the search query & wether its is called in initialization (true) or in searching (false)
    customDataFunction: {
      type: Function,
      default: null,
    },
  },
  data() {
    return {
      options: [],
      currentlang: 'en',
      oldFieldValue: [],
    };
  },
  async created() {
    this.currentlang = this.$store.state.app.language;
    await this.initializeOpttions();
    this.oldFieldValue = this.fieldValue;
  },
  methods: {
    async initializeOpttions() {
      if (!this.fieldValue) return;
      if (typeof this.customDataFunction === 'function') {
        this.options = await this.customDataFunction(this.fieldValue, true);
        return;
      }
      if (
        typeof this.fieldValue === 'string' ||
        typeof this.fieldValue === 'number'
      ) {
        // TODO refactor data fetching method in one place and provide a prop accepting Function for this also
        this.options = await this.getOptionsDataDefault(
          `${this.initializeurl}=${this.fieldValue}`
        );
      } else if (Array.isArray(this.fieldValue)) {
        this.options = await this.getOptionsDataDefault(
          `${this.initializeurl}=${this.fieldValue.join(',')}`
        );
      } else {
        this.options = [];
      }
    },
    async remoteMethod(query) {
      if (query.length > 2) {
        if (typeof this.customDataFunction === 'function') {
          this.options = await this.customDataFunction(query, false);
          return;
        }
        this.options = await this.getOptionsDataDefault(
          `${this.searchUrl}=${query}`
        );
      } else {
        this.options = [];
      }
    },
    async getOptionsDataDefault(url) {
      return (await axios.get(url)).data.map(
        this.customMapFunction || this.mapResponseDefault
      );
    },
    mapResponseDefault(res) {
      let obj = {};
      obj[this.optionLabel || 'label'] = (x =>
        this.multilang ? JSON.parse(x) : x)(
        res[this.optionLabel] || res.label || res.name
      );
      obj[this.optionValue || 'id'] = res[this.optionValue] || res.id;
      return obj;
    },
  },
  watch: {
    async currentlang() {
      if (this.fieldValue != this.oldFieldValue) {
        await this.initializeOpttions();
        this.oldFieldValue = this.fieldValue;
      }
    },
  },
  render(h) {
    const optionsObject = {};
    optionsObject.style = 'width: 500px';
    optionsObject.props = {
      value: this.fieldValue,
      filterable: true,
      remote: true,
      multiple: this.multiple,
      remoteMethod: this.remoteMethod,
      reserveKeyword: true,
      placeholder: 'Select Something',
    };
    if (this.draggable) {
      optionsObject.attrs = {
        filterable: true,
        remote: true,
        multiple: this.multiple,
        remoteMethod: this.remoteMethod,
        reserveKeyword: true,
        clearable: true,
      };
    }
    optionsObject.on = {
      change: event => this.$emit('update:fieldValue', event),
    };
    return (
      <el-form-item
        label={
          this.label || this.prop.charAt(0).toUpperCase() + this.prop.slice(1)
        }
        prop={this.prop}
      >
        <el-tooltip
          class="item"
          effect="dark"
          content={
            this.tooltipMsg ||
            this.prop.charAt(0).toUpperCase() + this.prop.slice(1)
          }
          placement="right"
          tabindex={-1}
        >
          <i class="el-icon-info" />
        </el-tooltip>
        {this.multilang ? (
          <el-radio-group
            value={this.currentlang}
            onInput={event => {
              this.currentlang = event;
            }}
            size="mini"
          >
            {this.langs.map(lang => (
              <el-radio-button label={lang}></el-radio-button>
            ))}
          </el-radio-group>
        ) : (
          ''
        )}
        {h(
          `el-${this.draggable ? 'drag-' : ''}select`,
          optionsObject,
          this.options.map(item => (
            <el-option
              key={item[this.optionValue] || item[this.optionValue] || item}
              label={
                item[this.optionLabel][this.currentlang] ||
                item[this.optionLabel] ||
                item
              }
              value={item[this.optionValue] || item[this.optionValue] || item}
            />
          ))
        )}
      </el-form-item>
    );
  },
};
</script>

<style scoped>
.el-form-item {
  display: flex;
  align-items: center;
}
.el-tooltip {
  margin-right: 10px;
}

.el-radio-group {
  margin-right: 10px;
}
</style>

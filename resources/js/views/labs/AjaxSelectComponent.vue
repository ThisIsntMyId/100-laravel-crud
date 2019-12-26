<script>
import axios from 'axios';
import ElDragSelect from '@/components/DragSelect';
export default {
  data() {
    return {
      options: [],
    };
  },
  components: { ElDragSelect },
  props: {
    fieldValue: {
      type: [Array, Number, String],
      required: true,
    },
    getSelectedItems: {
      type: Function,
      required: true,
    },
    getSearchedItems: {
      type: Function,
      required: true,
    },
    optionValue: {
      type: String,
      default: 'value',
    },
    optionLabel: {
      type: String,
      default: 'label',
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    drag: {
      type: Boolean,
      default: false,
    },
  },
  async created() {
    this.options = await this.getSelectedItems(this.fieldValue);
  },
  methods: {
    async remoteMethod(query) {
      if (query.length > 2) {
        this.options = await this.getSearchedItems(query);
        console.log('TCL: remoteMethod -> this.options', this.options);
      } else {
        this.options = [];
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
    if (this.drag) {
      optionsObject.attrs = {
        filterable: true,
        remote: true,
        multiple: this.multiple,
        remoteMethod: this.remoteMethod,
        reserveKeyword: true,
      };
    }
    optionsObject.on = {
      change: event => this.$emit('update:fieldValue', event),
    };
    return (
      <div class="app-container">
        {h(
          `el-${this.drag ? 'drag-' : ''}select`,
          optionsObject,
          this.options.map(item => (
            <el-option
              key={item[this.optionValue] || item}
              label={item[this.optionLabel] || item}
              value={item[this.optionValue] || item}
            />
          ))
        )}
      </div>
    );
  },
};
</script>
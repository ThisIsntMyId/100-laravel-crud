<script>
// hard coded search param
import ElDragSelect from '@/components/DragSelect';

export default {
  components: { ElDragSelect },
  name: 'SelectFormComponent',
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
    src: {
      type: Array,
      default: () => [],
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    async: {
      type: Boolean,
      default: false,
    },
    asyncUrl: {
      type: String,
      default: '',
    },
    selectedValue: {
      type: String,
      default: 'value',
    },
    selectedLabel: {
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
  },
  render(h) {
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
        {h(
          `el-${this.draggable ? 'drag-' : ''}select`,
          {
            style: `width: ${this.fieldWidth}`,
            props: {
              value: this.fieldValue,
              multiple: this.multiple,
              clearable: this.multiple || this.draggable,
            },
            on: {
              change: event => this.$emit('update:fieldValue', event),
            },
          },
          this.src.map(item => (
            <el-option key={item} label={item} value={item} />
          ))
        )}
      </el-form-item>
    );
  },
};
</script>

<style>
</style>

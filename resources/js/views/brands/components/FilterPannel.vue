<script>
import FilterField from './FilterField';
import InputFilterComponent from './FilterComponents/InputFilterComponent';
import SelectFilterComponent from './FilterComponents/SelectFilterComponent';
import CheckBoxFilterComponent from './FilterComponents/CheckBoxFilterComponent';
import BooleanFilterComponent from './FilterComponents/BooleanFilterComponent';
import RadioFilterComponent from './FilterComponents/RadioFilterComponent';
import RangeFilterComponent from './FilterComponents/RangeFilterComponent';
import RateFilterComponent from './FilterComponents/RateFilterComponent';
import Resource from '@/api/resource';
const SectionResource = new Resource('sections');

export default {
  name: 'FilterPannel',
  components: {
    FilterField,
    InputFilterComponent,
    SelectFilterComponent,
    CheckBoxFilterComponent,
    BooleanFilterComponent,
    RadioFilterComponent,
    RangeFilterComponent,
    RateFilterComponent,
  },
  data() {
    return {
      dialogVisible: false,
      filterParams: {},
      defaultFilterValues: {},
      sort: {
        field: '',
        asc: true,
      },
      section_codes: [],
      filterPannelObj: {
        section_code: {
          default: [],
          type: 'Select',
          label: 'Section Code',
          src: 'section_codes',
          multiple: true,
        },
        language: {
          default: [],
          type: 'CheckBox',
          label: 'Language',
          src: [
            { value: 'en', label: 'English' },
            { value: 'ar', label: 'Arabic' },
            { value: 'mr', label: 'Marathi' },
          ],
        },
        title: {
          default: '',
          type: 'Input',
          label: 'Title',
        },
        content: {
          default: '',
          type: 'Input',
          label: 'Content',
        },
        bool: {
          default: false,
          type: 'Boolean',
          label: 'Boolean Value',
        },
        activity: {
          default: '',
          type: 'Radio',
          label: 'Activity',
          src: [
            { value: 'd', label: 'Dance' },
            { value: 'r', label: 'Read' },
            { value: 'p', label: 'Play' },
          ],
        },
        price: {
          default: [100, 300],
          type: 'Range',
          label: 'Price',
          max: 500,
          min: 0,
        },
        rating: {
          default: 0,
          type: 'Rate',
          label: 'Rate Us',
        },
        taxes: {
          default: 0,
          type: 'Input',
          label: 'Taxes',
          default: '',
        },
      },
    };
  },
  async created() {
    await this.getSectionCodes();
    this.defaultFilterValues = Object.entries(this.filterPannelObj).reduce(
      (acc, obj) => {
        acc[obj[0]] = obj[1].default;
        return acc;
      },
      {}
    );
    this.filterParams = JSON.parse(JSON.stringify(this.defaultFilterValues));
  },
  watch: {
    section_codes(newVal, oldVal) {
      this.filterPannelObj.section_code.src = newVal;
    },
  },
  methods: {
    handleSortChange(field) {
      this.sort.field = field;
      this.sort.asc = !this.sort.asc;
    },
    async getSectionCodes() {
      this.section_codes = (await SectionResource.list({})).map(
        item => item.code
      );
    },
    submitFilterParams() {
      alert();
      const nonEmptyFilterParams = Object.entries(this.filterParams).reduce(
        (acc, [key, val]) => {
          if (!(val === '' || val === [])) {
            acc[key] = val;
          }
          return acc;
        },
        {}
      );
      this.$emit('set-filter', {
        filters: nonEmptyFilterParams,
        sort: this.sort,
      });
    },
    resetFilters() {
      alert();
      this.filterParams = JSON.parse(JSON.stringify(this.defaultFilterValues));
      this.sort = {
        field: '',
        asc: true,
      };
      this.$emit('reset-filter');
    },
    handleDialog(event) {
      alert(event);
      this.dialogVisible = event;
    },
    getFilterComponents(h, filterName, filterDef) {
      return h(filterDef.type + 'FilterComponent', {
        props: {
          label: filterDef.label,
          'param-name': filterName,
          'param-value': this.filterParams[filterName],
          'sort-field': this.sort.field,
          'sort-asc': this.sort.asc,
          ...filterDef,
        },
        on: {
          'update:paramValue': event => {
            this.filterParams[filterName] = event;
          },
          'handle-sort-change': event => {
            this.handleSortChange(event);
          },
        },
      });
    },
  },
  render(h) {
    return (
      <div>
        <el-button
          type="primary"
          icon="el-icon-s-operation"
          onClick={() => {
            this.dialogVisible = true;
          }}
        >
          Filters
        </el-button>
        {h(
          'el-dialog',
          {
            props: { title: 'Filter Pannel', visible: this.dialogVisible },
            on: {
              'update:visible': () => {
                this.dialogVisible = false;
              },
            },
          },
          [
            <div>
              {Object.entries(this.filterPannelObj).map(filterObj =>
                this.getFilterComponents(h, filterObj[0], filterObj[1])
              )}
            </div>,
            <span slot="footer" class="dialog-footer">
              <el-button
                onClick={() => {
                  this.resetFilters();
                }}
              >
                Reset
              </el-button>
              <el-button
                type="primary"
                onClick={() => this.submitFilterParams()}
              >
                Filter
              </el-button>
            </span>,
          ]
        )}
      </div>
    );
  },
};
</script>

<style>
</style>

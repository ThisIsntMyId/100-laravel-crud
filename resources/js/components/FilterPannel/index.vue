<script>
import InputFilterComponent from './FilterComponents/InputFilterComponent';
import SelectFilterComponent from './FilterComponents/SelectFilterComponent';
import CheckBoxFilterComponent from './FilterComponents/CheckBoxFilterComponent';
import BooleanFilterComponent from './FilterComponents/BooleanFilterComponent';
import RadioFilterComponent from './FilterComponents/RadioFilterComponent';
import RangeFilterComponent from './FilterComponents/RangeFilterComponent';
import RateFilterComponent from './FilterComponents/RateFilterComponent';

export default {
  name: 'FilterPannel',
  components: {
    InputFilterComponent,
    SelectFilterComponent,
    CheckBoxFilterComponent,
    BooleanFilterComponent,
    RadioFilterComponent,
    RangeFilterComponent,
    RateFilterComponent,
  },
  props: {
    filterPannelObj: {
      type: Object,
      required: true,
    },
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
    };
  },
  async created() {
    this.defaultFilterValues = Object.entries(this.filterPannelObj).reduce(
      (acc, obj) => {
        acc[obj[0]] = obj[1].default;
        return acc;
      },
      {}
    );
    this.filterParams = JSON.parse(JSON.stringify(this.defaultFilterValues));
  },
  methods: {
    handleSortChange(field) {
      this.sort.field = field;
      this.sort.asc = !this.sort.asc;
    },
    submitFilterParams() {
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
          type='primary'
          icon='el-icons-operation'
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
            <span slot='footer' class='dialog-footer'>
              <el-button
                onClick={() => {
                  this.resetFilters();
                }}
              >
                Reset
              </el-button>
              <el-button
                type='primary'
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

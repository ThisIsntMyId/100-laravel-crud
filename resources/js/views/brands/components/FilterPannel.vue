<template>
  <div>
    <el-button type="primary" icon="el-icon-s-operation" @click="dialogVisible = true">Filters</el-button>
    <el-dialog title="Filter Blocks" :visible.sync="dialogVisible" width="700px">
      <div>
        <!-- section code -->
        <SelectFilterComponent
          label="Section Code"
          param-name="section_code"
          :param-value.sync="filterParams.section_code"
          :src="section_codes"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <CheckBoxFilterComponent
          label="Language"
          param-name="language"
          :param-value.sync="filterParams.language"
          :src="[{value: 'en', label: 'English'}, {value: 'ar', label: 'Arabic'}, {value: 'mr', label: 'Marathi'}]"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <InputFilterComponent
          label="Title"
          param-name="title"
          :param-value.sync="filterParams.title"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <InputFilterComponent
          label="Content"
          param-name="content"
          :param-value.sync="filterParams.content"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <InputFilterComponent
          label="Image"
          param-name="image"
          :param-value.sync="filterParams.image"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <InputFilterComponent
          label="Image Mobile"
          param-name="image_mobile"
          :param-value.sync="filterParams.image_mobile"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <InputFilterComponent
          label="Link"
          param-name="link"
          :param-value.sync="filterParams.link"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <InputFilterComponent
          label="Button Link"
          param-name="btn_title"
          :param-value.sync="filterParams.btn_title"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <BooleanFilterComponent
          label="bool"
          param-name="bool"
          :param-value.sync="filterParams.bool"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <RadioFilterComponent
          label="Activity"
          param-name="activity"
          :param-value.sync="filterParams.activity"
          :src="[{value: 'd', label: 'Dance'}, {value: 'r', label: 'Read'}, {value: 'p', label: 'Play'}]"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <RangeFilterComponent
          label="Price"
          param-name="range"
          :param-value.sync="filterParams.range"
          :max="500"
          :min="0"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <RateFilterComponent
          label="Rate Us?"
          param-name="rate"
          :max="10"
          :param-value.sync="filterParams.rate"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="resetFilters">Reset</el-button>
        <el-button type="primary" @click="submitFilterParams">Filter</el-button>
      </span>
    </el-dialog>
  </div>
</template>

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
      filterParams: {
        section_code: [],
        language: [],
        title: '',
        content: '',
        image: '',
        image_mobile: '',
        link: '',
        btn_title: '',
        // ---
        bool: false,
        activity: 'r',
        range: [0,200],
        rate: 0,
      },
      sort: {
        field: '',
        asc: true,
      },
      section_codes: [],
    };
  },
  async created() {
    await this.getSectionCodes();
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
      this.filterParams = {
        section_code: [],
        language: [],
        title: '',
        content: '',
        image: '',
        image_mobile: '',
        link: '',
        btn_title: '',
        // ---
        bool: false,
        activity:'',
        range: [0,200],
        rate: 0,
      };
      this.sort = {
        field: '',
        asc: true,
      };
      this.$emit('reset-filter');
    },
  },
};
</script>

<style>
</style>

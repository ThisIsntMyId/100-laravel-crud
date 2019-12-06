<template>
  <div>
    <el-button type="primary" icon="el-icon-s-operation" @click="dialogVisible = true">Filters</el-button>
    <el-dialog title="Filter Blocks" :visible.sync="dialogVisible" width="700px">
      <div>
        <!-- section code -->
        <FilterField
          label="Section Code"
          param-name="section_code"
          :param-value="filterParams.section_code"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        >
          <el-select
            v-model="filterParams.section_code"
            placeholder="Select section_code"
            multiple
            collapse-tags
            clearable
            style="width: 300px;"
          >
            <el-option v-for="item in section_codes" :key="item" :label="item" :value="item" />
          </el-select>
        </FilterField>
        <FilterField
          label="Language"
          param-name="language"
          :param-value="filterParams.language"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        >
          <el-checkbox-group v-model="filterParams.language">
            <el-checkbox label="en">English</el-checkbox>
            <el-checkbox label="ar">Arabic</el-checkbox>
          </el-checkbox-group>
        </FilterField>
        <FilterField
          label="Title"
          param-name="title"
          :param-value.sync="filterParams.title"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <FilterField
          label="Content"
          param-name="content"
          :param-value.sync="filterParams.content"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <FilterField
          label="Image"
          param-name="image"
          :param-value.sync="filterParams.image"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <FilterField
          label="Image Mobile"
          param-name="image_mobile"
          :param-value.sync="filterParams.image_mobile"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <FilterField
          label="Link"
          param-name="link"
          :param-value.sync="filterParams.link"
          :sort-field="sort.field"
          :sort-asc="sort.asc"
          @handle-sort-change="handleSortChange($event)"
        />
        <FilterField
          label="Button Link"
          param-name="btn_title"
          :param-value.sync="filterParams.btn_title"
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
import Resource from '@/api/resource';
const SectionResource = new Resource('sections');

export default {
  name: 'BlockFilter',
  components: {
    FilterField,
  },
  data() {
    return {
      dialogVisible: false,
      filterParams: {
        section_code: '',
        language: [],
        title: '',
        content: '',
        image: '',
        image_mobile: '',
        link: '',
        btn_title: '',
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
      console.log(nonEmptyFilterParams);
      this.$emit('filter-params', {
        filters: nonEmptyFilterParams,
        sort: this.sort,
      });
    },
    resetFilters() {
      this.filterParams = {
        section_code: '',
        language: [],
        title: '',
        content: '',
        image: '',
        image_mobile: '',
        link: '',
        btn_title: '',
      };
      this.sort = {
        field: '',
        asc: true,
      };
    },
  },
};
</script>

<style>
</style>

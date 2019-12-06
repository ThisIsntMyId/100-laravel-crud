<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="12">
        <!-- Content should go here for col 1 in rows in cards rows seperated by dividers-->
        <el-row>
          <el-card>
            <div slot="header">
              <span>Main Details</span>
            </div>
            <div>
              <span>Section Code</span>
              {{ blockData.section_code }}
            </div>
            <el-divider />
            <div>
              <span>Language</span>
              {{ blockData.language }}
            </div>
            <el-divider />
            <div>
              <span>Title</span>
              {{ blockData.title }}
            </div>
            <el-divider />
            <div>
              <span>Content</span>
              {{ blockData.content }}
            </div>
            <el-divider />
          </el-card>
        </el-row>
        <el-row>
          <el-card>
            <div slot="header">
              <span>Button Details</span>
            </div>
            <div>
              <span>Button Title</span>
              {{ blockData.btn_title }}
            </div>
            <el-divider />
            <div>
              <span>Button Link</span>
              {{ blockData.link }}
            </div>
            <el-divider />
          </el-card>
        </el-row>
      </el-col>
      <el-col :span="12">
        <!-- Content should go here for col 2 in rows in cards rows seperated by dividers-->
        <el-row>
          <el-card>
            <div slot="header">
              <span>Image Details</span>
            </div>
            <div>
              <span>Main Image</span>
              {{ blockData.image }}
            </div>
            <el-divider />
            <div>
              <span>Mobile Image</span>
              {{ blockData.image_mobile }}
            </div>
            <el-divider />
          </el-card>
        </el-row>
        <el-row>
          <el-card>
            <div style="display: flex; justify-content: start;">
              <el-button
                icon="el-icon-back"
                type="primary"
                circle
                @click="$router.push(`/blocks`)"
              />
              <el-button
                icon="el-icon-edit"
                type="success"
                circle
                @click="$router.push(`/blocks/edit/${$route.params.id}`)"
              />
              <el-button
                icon="el-icon-delete"
                type="danger"
                circle
                @click="handleDeleteClick($route.params.id)"
              />
            </div>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const BlockResource = new Resource('blocks');

export default {
  components: {},
  data() {
    return {
      blockData: {},
      loading: {
        blockData: false,
      },
    };
  },
  async created() {
    await this.getBlockData(this.$route.params.id);
  },
  methods: {
    async getBlockData(id) {
      try {
        this.loading.blockData = true;
        this.blockData = await BlockResource.get(id);
        this.loading.blockData = false;
      } catch (e) {
        await this.$router.push({ name: 'Page404' });
      }
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
  },
};
</script>

<style lang="scss" scoped>
.el-row .el-row {
  margin-bottom: 20px;

  div div {
    display: flex;
    justify-content: space-between;
  }
  span {
    font-size: 1.2rem;
    font-weight: bold;
  }
}

.el-divider {
  margin: 10px 0;
}
</style>

/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const categoriesRoutes = {
  path: '/categories',
  component: Layout,
  redirect: '/categories/index',
  name: 'Categories',
  meta: {
    title: 'Category',
    icon: 'admin',
  },
  children: [
    {
      path: '/categories/index',
      component: () => import('@/views/categories/CategoryList.vue'),
      name: 'CategoryList',
      meta: { title: 'CategoryList', noCache: true },
    },
    {
      path: '/categories/create',
      component: () => import('@/views/categories/CategoryForm.vue'),
      // name: 'Add Categories', //? for these view to not to show up in tags
      meta: { title: 'Add Category', noCache: true },
    },
    {
      path: '/categories/view/:id',
      component: () => import('@/views/categories/CategoryView.vue'),
      // name: 'View Category',
      hidden: true,
      meta: { title: 'View Category', noCache: true },
    },
    {
      path: '/categories/edit/:id',
      component: () => import('@/views/categories/CategoryForm.vue'),
      // name: 'Edit Category', //? for these view to not to show up in tags
      hidden: true,
      meta: { title: 'Edit Category', noCache: true },
    },
  ],
};

export default categoriesRoutes;

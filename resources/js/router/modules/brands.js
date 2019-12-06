/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const brandsRoutes = {
  path: '/brands',
  component: Layout,
  redirect: '/brands/index',
  name: 'Brands',
  meta: {
    title: 'Brand',
    icon: 'admin',
  },
  children: [
    {
      path: '/brands/index',
      component: () => import('@/views/brands/BrandList'),
      name: 'BrandList',
      meta: { title: 'BrandList', noCache: true },
    },
    {
      path: '/brands/create',
      component: () => import('@/views/brands/BrandForm'),
      // name: 'Add Brands', //? for these view to not to show up in tags
      meta: { title: 'Add Brand', noCache: true },
    },
    {
      path: '/brands/view/:id',
      component: () => import('@/views/brands/BrandView.vue'),
      // name: 'View Brand',
      hidden: true,
      meta: { title: 'View Brand', noCache: true },
    },
    {
      path: '/brands/edit/:id',
      component: () => import('@/views/brands/BrandForm.vue'),
      // name: 'Edit Brand', //? for these view to not to show up in tags
      hidden: true,
      meta: { title: 'Edit Brand', noCache: true },
    },
  ],
};

export default brandsRoutes;

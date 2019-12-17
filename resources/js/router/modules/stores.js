/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const storesRoutes = {
  path: '/stores',
  component: Layout,
  redirect: '/stores/index',
  name: 'Stores',
  meta: {
    title: 'Store',
    icon: 'admin',
  },
  children: [
    {
      path: '/stores/index',
      component: () => import('@/views/stores/StoreList'),
      name: 'StoreList',
      meta: { title: 'StoreList', noCache: true },
    },
    {
      path: '/stores/create',
      component: () => import('@/views/stores/StoreForm'),
      // name: 'Add Stores', //? for these view to not to show up in tags
      meta: { title: 'Add Store', noCache: true },
    },
    {
      path: '/stores/view/:id',
      component: () => import('@/views/stores/StoreView.vue'),
      // name: 'View store',
      hidden: true,
      meta: { title: 'View Store', noCache: true },
    },
    {
      path: '/stores/edit/:id',
      component: () => import('@/views/stores/StoreForm.vue'),
      // name: 'Edit Store', //? for these view to not to show up in tags
      hidden: true,
      meta: { title: 'Edit Store', noCache: true },
    },
  ],
};

export default storesRoutes;

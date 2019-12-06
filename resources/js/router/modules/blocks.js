/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const blocksRoutes = {
  path: '/blocks',
  component: Layout,
  redirect: '/blocks/index',
  name: 'Blocks',
  meta: {
    title: 'Block',
    icon: 'admin',
  },
  children: [
    {
      path: '/blocks/index',
      component: () => import('@/views/blocks/BlockList'),
      name: 'BlockList',
      meta: { title: 'BlockList', noCache: true },
    },
    {
      path: '/blocks/create',
      component: () => import('@/views/blocks/BlockForm'),
      // name: 'Add Blocks', //? for these view to not to show up in tags
      meta: { title: 'Add Block', noCache: true },
    },
    {
      path: '/blocks/view/:id',
      component: () => import('@/views/blocks/BlockView.vue'),
      // name: 'View Block',
      hidden: true,
      meta: { title: 'View Block', noCache: true },
    },
    {
      path: '/blocks/edit/:id',
      component: () => import('@/views/blocks/BlockForm.vue'),
      // name: 'Edit Block', //? for these view to not to show up in tags
      hidden: true,
      meta: { title: 'Edit Block', noCache: true },
    },
  ],
};

export default blocksRoutes;

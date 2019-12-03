/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const blocksRoutes = {
  path: '/blocks',
  component: Layout,
  redirect: '/blocks/index',
  name: 'Blocks',
  alwaysShow: true,
  meta: {
    title: 'Block',
    icon: 'admin',
  },
  children: [
    {
      path: '/blocks/index',
      component: () => import('@/views/blocks/index'),
      name: 'Blocks',
      meta: { title: 'Blocks', noCache: true },
    },
  ],
};

export default blocksRoutes;

/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const tagsRoutes = {
  path: '/tags',
  component: Layout,
  redirect: '/tags/index',
  name: 'Tags',
  meta: {
    title: 'Tag',
    icon: 'admin',
  },
  children: [
    {
      path: '/tags/index',
      component: () => import('@/views/tags/TagList.vue'),
      name: 'TagList',
      meta: { title: 'TagList', noCache: true },
    },
    {
      path: '/tags/create',
      component: () => import('@/views/tags/TagForm.vue'),
      // name: 'Add Tag', //? for these view to not to show up in tags
      meta: { title: 'Add Tag', noCache: true },
    },
    {
      path: '/tags/view/:id',
      component: () => import('@/views/tags/TagView.vue'),
      // name: 'View Tag',
      hidden: true,
      meta: { title: 'View Tag', noCache: true },
    },
    {
      path: '/tags/edit/:id',
      component: () => import('@/views/tags/TagForm.vue'),
      // name: 'Edit Tag', //? for these view to not to show up in tags
      hidden: true,
      meta: { title: 'Edit Tag', noCache: true },
    },
  ],
};

export default tagsRoutes;

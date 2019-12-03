/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const sectionsRoutes = {
  path: '/sections',
  component: Layout,
  redirect: '/sections/index',
  name: 'Sections',
  meta: {
    title: 'Section',
    icon: 'admin',
  },
  children: [
    {
      path: '/sections/index',
      component: () => import('@/views/sections/index'),
      name: 'Sections',
      meta: { title: 'Sections', noCache: true },
    },
  ],
};

export default sectionsRoutes;

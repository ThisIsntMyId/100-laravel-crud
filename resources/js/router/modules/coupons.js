/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const couponsRoutes = {
  path: '/coupons',
  component: Layout,
  redirect: '/coupons/index',
  name: 'Coupons',
  meta: {
    title: 'Coupon',
    icon: 'admin',
  },
  children: [
    {
      path: '/coupons/index',
      component: () => import('@/views/coupons/CouponList.vue'),
      name: 'CouponList',
      meta: { title: 'CouponList', noCache: true },
    },
    {
      path: '/coupons/create',
      component: () => import('@/views/coupons/CouponForm.vue'),
      // name: 'Add Coupon', //? for these view to not to show up in coupons
      meta: { title: 'Add Coupon', noCache: true },
    },
    {
      path: '/coupons/view/:id',
      component: () => import('@/views/coupons/CouponView.vue'),
      // name: 'View Coupon',
      hidden: true,
      meta: { title: 'View Coupon', noCache: true },
    },
    {
      path: '/coupons/edit/:id',
      component: () => import('@/views/coupons/CouponForm.vue'),
      // name: 'Edit Coupon', //? for these view to not to show up in coupons
      hidden: true,
      meta: { title: 'Edit Coupon', noCache: true },
    },
  ],
};

export default couponsRoutes;

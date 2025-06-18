import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ShopView from '@/views/ShopView.vue'
import CartView from '@/views/CartView.vue'
import ExampleView from '@/views/ExampleView.vue'
import LoginView from '@/views/LoginView.vue'
import CheckoutView from '@/views/CheckoutView.vue'
import SuccessOrderView from '@/views/SuccessOrderView.vue'
import OrderView from '@/views/OrderView.vue'
import OrderDetailView from '@/views/OrderDetailView.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/shop',
      name: 'shop',
      component: ShopView,
    },
    {
      path: '/cart',
      name: 'cart',
      component: CartView,
      meta: { requiresAuth: true },
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: CheckoutView,
      meta: { requiresAuth: true, hideNavbar: true, hideFooter: true },
      beforeEnter: (to, from, next) => {
        const cartItems = localStorage.getItem('cartItems')
        if (cartItems) {
          next()
        } else {
          next('/cart')
        }
      },
    },
    {
      path: '/successOrder',
      name: 'successOrder',
      component: SuccessOrderView,
      meta: { requiresAuth: true, hideFooter: true, hideNavbar: true },
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {
        hideNavbar: true,
        hideFooter: true,
      },
    },
    {
      path: '/orders',
      name: 'orders',
      component: OrderView,
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/orders/:id',
      name: 'order-details',
      component: OrderDetailView,
      meta: {
        requiresAuth: true,
        hideNavbar: true,
        hideFooter: true,
      },
    },
    {
      path: '/example',
      name: 'example',
      component: ExampleView,
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }
    return { top: 0 }
  },
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({
      path: '/login',
      query: { redirect: to.fullPath },
    })
  } else {
    next()
  }
})

export default router

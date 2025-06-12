import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ShopView from '@/views/ShopView.vue'
import CartView from '@/views/CartView.vue'
import ExampleView from '@/views/ExampleView.vue'
import LoginView from '@/views/LoginView.vue'
import CheckoutView from '@/views/CheckoutView.vue'
import SuccessOrderView from '@/views/SuccessOrderView.vue'

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
      meta: { requiresAuth: true },
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
      name: 'SuccessOrder',
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
router.beforeEach(async (to, from, next) => {
  // Periksa jika rute memerlukan autentikasi
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('authToken')

    if (!token) {
      return next({
        path: '/login',
        query: { redirect: to.fullPath }, // Simpan rute tujuan untuk redirect setelah login
      })
    }

    try {
      const response = await fetch('http://localhost:8000/api/authenticated', {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      })

      if (response.ok) {
        next()
      } else {
        localStorage.removeItem('authToken')
        return next({
          path: '/login',
          query: { redirect: to.fullPath },
        })
      }
    } catch (error) {
      console.error('Error validasi token:', error)
      // Jika terjadi kesalahan (misalnya, jaringan), hapus token dan arahkan ke login
      localStorage.removeItem('authToken')
      return next({
        path: '/login',
        query: { redirect: to.fullPath },
      })
    }
  } else {
    // Rute tidak memerlukan autentikasi, izinkan akses
    next()
  }
})

export default router

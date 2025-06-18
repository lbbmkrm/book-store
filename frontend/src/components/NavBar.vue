<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import HamburgerIcon from './icons/hamburger-icon.vue'
import CrossIcon from './icons/cross-icon.vue'
import HomeIcon from './icons/home-icon.vue'
import ShopIcon from './icons/shop-icon.vue'
import AboutIcon from './icons/about-icon.vue'
import CartIcon from './icons/cart-icon.vue'
import OrderIcon from './icons/order-icon.vue'
import ProfileIcon from './icons/profile-icon.vue'
import SunIcon from './icons/sun-icon.vue'
import MoonIcon from './icons/moon-icon.vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LogoutIcon from './icons/logout-icon.vue'
import UserIcon from './icons/user-icon.vue'
import { useCartStore } from '@/stores/cart'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()
const toast = useToast()
const isMenuOpen = ref(false)
const isDarkMode = ref(false)
const isProfileDropdownOpen = ref(false)
const user = computed(() => authStore.user)

const cartItemsCount = computed(() => {
  return cartStore.cartItems.reduce((total, item) => total + item.quantity, 0)
})

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}

const toggleProfileDropdown = () => {
  if (!user.value) {
    router.push('/login')
    return
  }
  isProfileDropdownOpen.value = !isProfileDropdownOpen.value
}

const closeProfileDropdown = () => {
  isProfileDropdownOpen.value = false
}

const logout = async () => {
  try {
    await authStore.logout()
    console.log('Success Logout')
    window.location.reload()
  } catch (error) {
    console.log(error)
    console.log(localStorage.getItem('authItem'))
    toast.error('Gagal logout coba lagi', {
      position: 'top-center',
      timeout: 2000,
      closeOnClick: true,
      pauseOnFocusLoss: true,
      pauseOnHover: false,
      draggable: true,
      draggablePercent: 0.5,
      showCloseButtonOnHover: false,
      hideProgressBar: true,
      closeButton: false,
      icon: true,
      rtl: false,
    })
  }
}

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  if (isDarkMode.value) {
    document.documentElement.setAttribute('data-theme', 'dark')
  } else {
    document.documentElement.removeAttribute('data-theme')
  }
  localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
}

onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  if (
    savedTheme === 'dark' ||
    (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)
  ) {
    isDarkMode.value = true
    document.documentElement.setAttribute('data-theme', 'dark')
  } else {
    isDarkMode.value = false
    document.documentElement.removeAttribute('data-theme')
  }

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.profile-dropdown-container')) {
      closeProfileDropdown()
    }
  })
})

const navigationButtonClass =
  'flex items-center cursor-pointer rounded-md px-3 py-2 text-gray-700 transform transition-transform hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 ease-in-out hover:scale-105 gap-2 dark:text-white dark:hover:text-blue-400 dark:hover:bg-gray-700'
</script>

<template>
  <nav class="fixed w-full top-0 left-0 right-0 bg-transparent dark:bg-gray-800 shadow-md z-50">
    <div
      class="w-full p-4 flex justify-between items-center dark:text-white transition-color"
      :class="isMenuOpen ? 'bg-white' : 'backdrop-blur-lg'"
    >
      <!-- Logo -->
      <div class="logo flex items-center dark:text-white">
        <a href="/" class="font-header text-xl md:text-3xl font-light">mystore</a>
      </div>
      <!-- Center Nav -->
      <div
        class="font-label center-navigation hidden md:flex gap-8 text-xl items-center dark:text-white"
      >
        <RouterLink to="/" :class="navigationButtonClass"><HomeIcon />Home</RouterLink>
        <RouterLink to="/shop" :class="navigationButtonClass"><ShopIcon />Shop</RouterLink>
        <RouterLink :class="navigationButtonClass"><AboutIcon />About</RouterLink>
      </div>
      <!-- Right Nav -->
      <div class="right-navigation hidden md:flex items-center justify-end gap-4 dark:text-white">
        <!-- Cart -->
        <RouterLink to="/cart" :class="navigationButtonClass" class="relative">
          <CartIcon />
          <!-- Badge cart -->
          <span
            v-if="cartItemsCount > 0"
            class="absolute top-2 right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[20px] h-5"
          >
            {{ cartItemsCount > 99 ? '99+' : cartItemsCount }}
          </span>
        </RouterLink>

        <RouterLink to="/orders" :class="navigationButtonClass">
          <OrderIcon />
        </RouterLink>

        <!-- Profile Dropdown Container -->
        <div class="relative profile-dropdown-container">
          <button @click="toggleProfileDropdown" :class="navigationButtonClass">
            <ProfileIcon />
          </button>

          <!-- Profile Dropdown -->
          <div
            v-if="isProfileDropdownOpen"
            class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 transform transition-all duration-200 ease-in-out"
          >
            <!-- User Info -->
            <div
              class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex gap-4 items-center"
            >
              <div class="h-6 w-6">
                <UserIcon class="w-full h-full text-gray-500 dark:text-gray-100" />
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ user ? user.name : '' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ user ? user.email : '' }}</p>
              </div>
            </div>

            <!-- Dropdown Menu Items -->
            <div class="py-1">
              <RouterLink
                to="/profile"
                @click="closeProfileDropdown"
                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
              >
                <ProfileIcon class="h-4 w-4" />
                View Profile
              </RouterLink>

              <button
                @click="logout"
                class="cursor-pointer w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
              >
                <LogoutIcon class="h-4 w-4" />
                Logout
              </button>
            </div>
          </div>
        </div>

        <!-- Tombol Toggle Dark Mode (Desktop) -->
        <button @click="toggleDarkMode" :class="navigationButtonClass" title="Toggle Dark Mode">
          <SunIcon v-if="isDarkMode" />
          <MoonIcon v-else />
        </button>
      </div>

      <!-- Mobile Nav -->
      <div class="mobile-nav md:hidden flex items-center dark:text-white">
        <button
          @click="toggleMenu"
          class="cursor-pointer text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 ease transform hover:scale-110 relative rounded-md p-2 dark:text-white dark:hover:text-blue-400 dark:hover:bg-gray-700"
        >
          <HamburgerIcon v-if="!isMenuOpen" />
          <CrossIcon v-if="isMenuOpen" />
        </button>
      </div>

      <!-- Mobile menu overlay -->
      <div
        v-if="isMenuOpen"
        class="fixed inset-0 bg-transparent backdrop-blur-sm z-10 md:hidden transition-opacity duration-400 dark:bg-gray-800 dark:bg-opacity-50 dark:backdrop-blur-sm"
        @click="closeMenu"
      ></div>
      <!-- Mobile Menu -->
      <div
        class="z-20 fixed min-w-[60%] transform transition-all perspective-origin-right py-2 right-0 top-0 h-full bg-white shadow-md duration-400 ease-in-out md:hidden dark:bg-gray-800 dark:text-white"
        :class="isMenuOpen ? 'translate-x-0' : 'translate-x-full'"
      >
        <div class="flex flex-col w-full p-4 gap-4 dark:bg-gray-800 dark:text-white">
          <div class="flex justify-end mb-4 rounded-full dark:bg-gray-800 dark:text-white">
            <button
              @click="closeMenu"
              :class="navigationButtonClass"
              class="hover:transform hover:rotate-180 transition-all ease-in-out duration-400 dark:hover:bg-gray-800 dark:hover:text-white dark:text-white dark:hover:scale-110"
            >
              <CrossIcon />
            </button>
          </div>

          <!-- User Info Section (Mobile) -->
          <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700 mb-2">
            <p class="text-sm font-medium text-gray-900 dark:text-white">
              {{ user ? user.name : '' }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ user ? user.email : '' }}</p>
          </div>

          <RouterLink to="/" @click="closeMenu" :class="navigationButtonClass">
            <HomeIcon />Home
          </RouterLink>
          <RouterLink to="/shop" @click="closeMenu" :class="navigationButtonClass">
            <ShopIcon />Shop
          </RouterLink>
          <RouterLink @click="closeMenu" :class="navigationButtonClass">
            <AboutIcon />About
          </RouterLink>

          <!-- Cart dengan Badge (Mobile) -->
          <RouterLink to="/cart" @click="closeMenu" :class="navigationButtonClass" class="relative">
            <CartIcon />Cart
            <span
              v-if="cartItemsCount > 0"
              class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[20px] h-5"
            >
              {{ cartItemsCount > 99 ? '99+' : cartItemsCount }}
            </span>
          </RouterLink>

          <RouterLink to="/orders" @click="closeMenu" :class="navigationButtonClass">
            <OrderIcon />Orders
          </RouterLink>
          <RouterLink to="/profile" @click="closeMenu" :class="navigationButtonClass">
            <ProfileIcon />Profile
          </RouterLink>

          <!-- Logout Button (Mobile) -->
          <button
            @click="logout"
            class="flex items-center cursor-pointer rounded-md px-3 py-2 text-red-600 dark:text-red-400 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 ease-in-out hover:scale-105 gap-2"
          >
            <LogoutIcon class="w-4 h-4" />
            Logout
          </button>

          <!-- Tombol Toggle Dark Mode (Mobile) -->
          <button @click="toggleDarkMode" :class="navigationButtonClass" title="Toggle Dark Mode">
            <SunIcon v-if="isDarkMode" />
            <MoonIcon v-else />
            {{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

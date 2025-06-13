<script setup>
import { ref, onMounted } from 'vue'
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
import { RouterLink } from 'vue-router'

const isMenuOpen = ref(false)
const isDarkMode = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}
const closeMenu = () => {
  isMenuOpen.value = false
}

// Fungsi untuk toggle dark mode
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  if (isDarkMode.value) {
    document.documentElement.setAttribute('data-theme', 'dark')
  } else {
    document.documentElement.removeAttribute('data-theme')
  }
  localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
}

// Inisialisasi tema saat komponen dimuat
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
})

const navigationButtonClass =
  'flex items-center cursor-pointer rounded-md px-3 py-2 text-gray-700 transform transition-transform hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 ease-in-out hover:scale-105 gap-2 dark:text-white dark:hover:text-blue-400 dark:hover:bg-gray-700'
</script>

<template>
  <nav class="fixed w-full top-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-md z-50">
    <div class="w-full p-4 flex justify-between items-center dark:text-white">
      <!-- Logo -->
      <div class="logo flex items-center dark:text-white">
        <h1 class="font-header text-xl md:text-3xl font-light">mystore</h1>
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
        <RouterLink to="/cart" :class="navigationButtonClass">
          <CartIcon />
        </RouterLink>
        <RouterLink to="/orders" :class="navigationButtonClass">
          <OrderIcon />
        </RouterLink>
        <button :class="navigationButtonClass">
          <ProfileIcon />
        </button>
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
          <RouterLink to="/" @click="closeMenu" :class="navigationButtonClass">
            <HomeIcon />Home
          </RouterLink>
          <RouterLink to="/shop" @click="closeMenu" :class="navigationButtonClass">
            <ShopIcon />Shop
          </RouterLink>
          <RouterLink @click="closeMenu" :class="navigationButtonClass">
            <AboutIcon />About
          </RouterLink>
          <RouterLink to="/cart" @click="closeMenu" :class="navigationButtonClass">
            <CartIcon />Cart
          </RouterLink>
          <RouterLink to="/orders" @click="closeMenu" :class="navigationButtonClass">
            <OrderIcon />Orders
          </RouterLink>
          <RouterLink @click="closeMenu" :class="navigationButtonClass">
            <ProfileIcon />Profile
          </RouterLink>
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

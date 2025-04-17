<script setup>
import { ref } from 'vue'
import HamburgerIcon from './icons/hamburger-icon.vue'
import CrossIcon from './icons/cross-icon.vue'
import HomeIcon from './icons/home-icon.vue'
import ShopIcon from './icons/shop-icon.vue'
import AboutIcon from './icons/about-icon.vue'
import CartIcon from './icons/cart-icon.vue'
import OrderIcon from './icons/order-icon.vue'
import ProfileIcon from './icons/profile-icon.vue'
import { RouterLink } from 'vue-router'

const isMenuOpen = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}
const closeMenu = () => {
  isMenuOpen.value = false
}

const navigationButtonClass =
  'flex items-center cursor-pointer rounded-md px-3 py-2 text-gray-700 transform transition-transform hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 ease-in-out  hover:scale-105 gap-2 '
</script>
<template>
  <nav class="fixed w-full top-0 left-0 right-0 bg-white">
    <div class="w-full p-4 flex justify-between items-center">
      <!-- Logo -->
      <div class="logo flex item-center">
        <h1 class="font-serif text-xl md:text-3xl font-light">mystore</h1>
      </div>
      <!-- Center Nav -->
      <div class="center-navigation hidden md:flex gap-8 text-xl items-center">
        <RouterLink to="/" :class="navigationButtonClass"><HomeIcon />Home</RouterLink>
        <RouterLink to="/shop" :class="navigationButtonClass"><ShopIcon />Shop</RouterLink>
        <RouterLink :class="navigationButtonClass"><AboutIcon />About</RouterLink>
      </div>
      <!-- Right Nav -->
      <div class="right-navigation hidden md:flex item-center justify-end gap-4">
        <RouterLink to="/cart" :class="navigationButtonClass">
          <CartIcon />
        </RouterLink>
        <button :class="navigationButtonClass">
          <OrderIcon />
        </button>
        <button :class="navigationButtonClass">
          <ProfileIcon />
        </button>
      </div>

      <!-- Mobile Nav -->
      <div class="mobile-nav md:hidden flex item-center">
        <button
          @click="toggleMenu"
          class="cursor-pointer text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 ease transform hover:sclae-110 relative"
        >
          <HamburgerIcon v-if="!isMenuOpen" />
          <CrossIcon v-if="isMenuOpen" />
        </button>
      </div>

      <!-- Mobile menu overlay -->
      <div
        v-if="isMenuOpen"
        class="fixed inset-0 bg-transparent backdrop-blur-sm z-10 md:hidden transition-opacity duration-400"
        @click="closeMenu"
      ></div>
      <!-- Mobile Menu -->
      <div
        class="z-20 fixed min-w-[60%] tranform transition-all perspective-origin-right py-2 right-0 top-0 h-full bg-white shadow-md duration-400 ease-in-out md:hidden"
        :class="isMenuOpen ? 'translate-x-0' : 'translate-full'"
      >
        <div class="flex flex-col w-full p-4 gap-4">
          <div class="flex justify-end mb-4 rounded-full">
            <button
              @click="closeMenu"
              :class="navigationButtonClass"
              class="hover:transform hover:rotate-180 transition-all ease-in-out duration-400"
            >
              <CrossIcon />
            </button>
          </div>
          <RouterLink to="/" @click="closeMenu" :class="navigationButtonClass"
            ><HomeIcon />Home</RouterLink
          >
          <RouterLink to="/shop" @click="closeMenu" :class="navigationButtonClass"
            ><ShopIcon />Shop</RouterLink
          >
          <RouterLink @click="closeMenu" :class="navigationButtonClass"
            ><AboutIcon />About</RouterLink
          >
          <RouterLink to="/cart" @click="closeMenu" :class="navigationButtonClass"
            ><CartIcon />Cart</RouterLink
          >
          <RouterLink @click="closeMenu" :class="navigationButtonClass"
            ><OrderIcon />Orders</RouterLink
          >
          <RouterLink @click="closeMenu" :class="navigationButtonClass"
            ><ProfileIcon />Profile</RouterLink
          >
        </div>
      </div>
    </div>
  </nav>
</template>

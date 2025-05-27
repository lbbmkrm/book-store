<script setup>
import { RouterLink, RouterView } from 'vue-router'
import NavBar from './components/NavBar.vue'
import Footer from './components/Footer.vue'
</script>

<template>
  <NavBar v-if="!$route.meta.hideNavbar" class="z-10" />
  <RouterView :class="$route.path === '/login' ? '' : 'mt-12 mb-24'" />
  <Footer v-if="!$route.meta.hideFooter" />
</template>

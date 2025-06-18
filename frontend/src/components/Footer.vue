<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import GoogleIcon from './icons/google-icon.vue'
import InstagramIcon from './icons/instagram-icon.vue'
import FacebookIcon from './icons/facebook-icon.vue'
import TiktokIcon from './icons/tiktok-icon.vue'
const apiUrl = import.meta.env.VITE_API_SERVER
const categories = ref([])

const fetchCategories = async function () {
  try {
    const result = await axios.get(`${apiUrl}/categories`)
    categories.value = result.data.data
  } catch (error) {
    console.log(error?.response?.data?.message || error.message)
    categories.value = []
  }
}

onMounted(() => {
  fetchCategories()
})
</script>
<template>
  <footer class="bg-white text-gray-800 dark:bg-gray-800 dark:text-white py-16 flex items-end">
    <div class="container mx-auto px-4 max-w-6xl">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Company Info -->
        <div class="space-y-4">
          <h3 class="text-xl font-bold mb-4">BookStore</h3>
          <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
            Toko buku online terpercaya dengan koleksi terlengkap. Kami menyediakan berbagai jenis
            buku berkualitas untuk semua kalangan pembaca.
          </p>
          <div class="flex space-x-3 pt-2 w-full justify-evenly">
            <a
              href="#"
              class="w-4 h-4 bg-transparent rounded-full flex items-center justify-center text-gray-600 dark:text-gray-50 hover:text-white transition"
              ><GoogleIcon
            /></a>
            <a
              href="#"
              class="w-4 h-4 bg-transparent rounded-full flex items-center justify-center text-gray-600 dark:text-gray-50 hover:text-white transition"
              ><InstagramIcon
            /></a>
            <a
              href="#"
              class="w-4 h-4 bg-transparent rounded-full flex items-center justify-center text-gray-600 dark:text-gray-50 hover:text-white transition"
              ><FacebookIcon
            /></a>
            <a
              href="#"
              class="w-4 h-4 bg-transparent rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white transition"
              ><TiktokIcon
            /></a>
          </div>
        </div>

        <!-- Categories -->
        <div class="space-y-4">
          <h3 class="text-xl font-bold mb-4">Kategori</h3>
          <ul class="space-y-3">
            <li v-for="label in categories" :key="label">
              <a
                href="#"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition flex items-center group"
              >
                <span
                  class="text-xs text-gray-400 dark:text-gray-500 mr-2 group-hover:text-gray-500 dark:group-hover:text-gray-300"
                  >▶</span
                >
                {{ label.name }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Help -->
        <div class="space-y-4">
          <h3 class="text-xl font-bold mb-4">Bantuan</h3>
          <ul class="space-y-3">
            <li
              v-for="label in [
                'Cara Pemesanan',
                'Metode Pembayaran',
                'Info Pengiriman',
                'Kebijakan Retur',
                'FAQ',
              ]"
              :key="label"
            >
              <a
                href="#"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition flex items-center group"
              >
                <span
                  class="text-xs text-gray-400 dark:text-gray-500 mr-2 group-hover:text-gray-500 dark:group-hover:text-gray-300"
                  >▶</span
                >
                {{ label }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact & Newsletter -->
        <div class="space-y-6">
          <div class="space-y-3 text-sm">
            <h3 class="text-xl font-bold">Hubungi Kami</h3>
            <div class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
              <span class="text-sm">📍</span> <span>Jl. Banyak Cakap No. 123, Medan</span>
            </div>
            <div class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
              <span class="text-sm">📞</span> <span>(021) 1234-5678</span>
            </div>
            <div class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
              <span class="text-sm">✉</span> <span>info@bookstore.com</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom -->
      <div
        class="border-t border-gray-300 dark:border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0"
      >
        <p class="text-gray-500 text-sm">&copy; 2025 BookStore. Semua hak cipta dilindungi.</p>
        <div class="flex flex-wrap justify-center gap-6 text-sm">
          <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition"
            >Kebijakan Privasi</a
          >
          <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition"
            >Syarat & Ketentuan</a
          >
          <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition"
            >Bantuan</a
          >
          <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition"
            >Sitemap</a
          >
        </div>
      </div>
    </div>
  </footer>
</template>

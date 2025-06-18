<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import GoogleIcon from './icons/google-icon.vue'
import FacebookIcon from './icons/facebook-icon.vue'
import InstagramIcon from './icons/instagram-icon.vue'
import TiktokIcon from './icons/tiktok-icon.vue'
import MapMarkerIcon from './icons/map-marker-icon.vue'
import PhoneIcon from './icons/phone-icon.vue'
import EmailIcon from './icons/email-icon.vue'
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
  <footer class="bg-white text-gray-800 dark:bg-gray-800 dark:text-white py-16 relative">
    <div class="container mx-auto px-4 max-w-6xl relative z-10">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Company Info -->
        <div class="space-y-4">
          <h3 class="text-xl font-bold mb-4">BookStore</h3>
          <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
            Toko buku online terpercaya dengan koleksi terlengkap. Kami menyediakan berbagai jenis
            buku berkualitas untuk semua kalangan pembaca.
          </p>
          <div class="flex space-x-3 pt-2">
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              ><GoogleIcon class="w-4 h-4"
            /></a>
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              ><FacebookIcon class="h-4 w-4"
            /></a>
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              ><InstagramIcon class="h-4 w-4"
            /></a>
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              ><TiktokIcon class="h-4 w-4"
            /></a>
          </div>
        </div>

        <!-- Categories -->
        <div class="space-y-4 pl-8">
          <h3 class="text-xl font-bold mb-4">Kategori</h3>
          <ul class="space-y-3">
            <li v-for="label in categories" :key="label" class="list-disc">
              <a
                href="#"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition flex items-center group"
              >
                {{ label.name }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Help -->
        <div class="space-y-4 pl-8 lg:pl-4">
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
              class="list-disc"
            >
              <a
                href="#"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition flex items-center group"
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
              <MapMarkerIcon class="w-4 h-4" /> <span>Jl. Banyak Kuah No.123, Medan</span>
            </div>
            <div class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
              <PhoneIcon class="h-4 w-4" /> <span>(062) 1234-5678</span>
            </div>
            <div class="flex items-center space-x-3 text-gray-600 dark:text-gray-400">
              <EmailIcon class="h-4 w-4" /> <span>info@bookstore.com</span>
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

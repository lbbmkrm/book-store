<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
const categories = ref([])

const fetchCategories = async function () {
  try {
    const result = await axios.get('http://127.0.0.1:8000/api/categories') // perbaiki juga URL
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
  <footer
    class="min-h-screen bg-white text-gray-800 dark:bg-gray-800 dark:text-white py-16 relative flex items-end"
  >
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
              >📱</a
            >
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              >👤</a
            >
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              >📸</a
            >
            <a
              href="#"
              class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-500 transition"
              >🐦</a
            >
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
              <span class="text-sm">📍</span> <span>Jl. Buku Raya No. 123, Jakarta</span>
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

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import cartPlusIcon from './icons/cart-plus-icon.vue'
const topBooks = ref([])
async function fetchTopProducts() {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/top-products')
    const productsObejct = await res.json()
    topBooks.value = productsObejct.data
  } catch (err) {
    console.log('error' + err)
  }
}
const mockBook = ref('/assets/img/mock-book.jpg')
onMounted(fetchTopProducts)
</script>
<template>
  <section class="py-16 min-h-screen flex item-center mb-24 md:mb-0">
    <div class="container max-w-6xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-serif font-bold text-gray-900">Buku Unggulan</h2>
        <p class="text-gray-600 mt-2">Pilihan buku terbaik yang paling diminati pembaca</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="book in topBooks"
          :key="book.id"
          class="rounded-lg shadow-lg overflow-hidden hover:shadow-lg transition-shadow duration-300"
        >
          <div class="h-64 overflow-hidden p-4">
            <img :src="mockBook" :alt="book.title" class="rounded-lg w-full h-full object-cover" />
          </div>
          <div class="p-4">
            <h3 class="font-bold font-serif text-gray-900 text-xl">{{ book.title }}</h3>
            <p class="italic font-mono text-gray-500">{{ book.author }}</p>
            <div class="grid grid-cols-2 item center justify-between">
              <span class="font-serif text-light self-center text-indigo-700"
                >Rp. {{ Number(book.price).toLocaleString('id-ID') }}</span
              >
              <div class="flex justify-end">
                <button
                  class="cursor-pointer px-5 py-2 font-monospace text-gray-800 bg-slate-50 hover:bg-sky-100"
                >
                  <cartPlusIcon />
                </button>
              </div>
              <button
                class="col-span-2 md:flex-1 cursor-pointer px-6 py-2 bg-indigo-600 text-white hover:bg-indigo-400 rounded-lg"
              >
                Beli
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-12">
        <RouterLink
          to="/shop"
          class="cursor-pointer border border-indigo-600 text-indigo-600 px-6 py-3 rounded hover:bg-indigo-600 hover:text-white transition-all delay-100 duration-200"
        >
          Lihat Semua Buku
        </RouterLink>
      </div>
    </div>
  </section>
</template>

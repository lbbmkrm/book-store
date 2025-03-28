<script setup>
import { ref } from 'vue'
const topBooks = ref([
  {
    id: 1,
    title: 'Money and Milk',
    author: 'John Doe',
    price: 100_000,
    image: '/assets/img/book-money-and-milk.jpg',
  },
  {
    id: 1,
    title: 'Money and Milk',
    author: 'John Doe',
    price: 100_000,
    image: '/assets/img/book-money-and-milk.jpg',
  },
  {
    id: 1,
    title: 'Money and Milk',
    author: 'John Doe',
    price: 100_000,
    image: '/assets/img/book-money-and-milk.jpg',
  },
  {
    id: 1,
    title: 'Money and Milk',
    author: 'John Doe',
    price: 100_000,
    image: '/assets/img/book-money-and-milk.jpg',
  },
])
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
            <img
              :src="book.image"
              :alt="book.title"
              class="rounded-lg w-full h-full object-cover"
            />
          </div>
          <div class="p-4">
            <h3 class="font-bold font-serif text-gray-900 text-xl">{{ book.title }}</h3>
            <p class="italic font-mono text-gray-500">{{ book.author }}</p>
            <div class="grid grid-cols-2 item center justify-between gap-4">
              <span class="font-serif font-semibold self-center text-indigo-700"
                >Rp. {{ book.price }}</span
              >
              <div class="flex justify-end">
                <button
                  class="cursor-pointer px-5 py-2 font-monospace text-gray-800 bg-slate-50 hover:bg-sky-100"
                >
                  +Add
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
        <button
          class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded hover:bg-indigo-50 transition-colors"
        >
          Lihat Semua Buku
        </button>
      </div>
    </div>
  </section>
</template>

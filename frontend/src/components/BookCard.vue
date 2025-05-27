<script setup>
import { defineProps } from 'vue'
import axios from 'axios'
import CartPlusIcon from '@/components/icons/cart-plus-icon.vue'

const mockBook = '/assets/img/mock-book.jpg'
const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}

const addToCart = async (book) => {
  try {
    const response = await axios.post(
      'http://127.0.0.1:8000/api/cart',
      { bookId: book.id },
      {
        headers: {
          'Content-Type': 'application/json',
        },
      },
    )
    console.log('Berhasil ditambahkan ke keranjang:', response.data)
  } catch (error) {
    console.error('Error menambahkan ke keranjang:', error.message)
  }
}
</script>
<template>
  <div
    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
  >
    <!-- Book Image -->
    <div class="relative h-64 flex items-center justify-center">
      <img :src="mockBook" :alt="book.title" class="w-full h-full object-cover" />

      <!-- Category Badge -->
      <div class="absolute top-3 left-3">
        <span class="bg-white/90 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
          {{ book.category.name }}
        </span>
      </div>

      <!-- Stock Status -->
      <div class="absolute bottom-3 right-3">
        <span
          class="px-2 py-1 rounded-full text-xs font-medium"
          :class="book.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
        >
          {{ book.stock > 0 ? `Stok: ${book.stock}` : 'Habis' }}
        </span>
      </div>
    </div>

    <!-- Book Details -->
    <div class="p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-2 min-h-14">
        {{ book.title }}
      </h3>

      <p class="text-sm text-gray-600 mb-3">
        oleh <span class="font-medium">{{ book.author }}</span>
      </p>

      <p class="text-sm text-gray-700 mb-4">
        {{ book.description }}
      </p>

      <!-- Price -->
      <div class="flex items-center justify-between mb-4">
        <div>
          <span class="text-2xl font-bold text-blue-600"> Rp {{ formatPrice(book.price) }} </span>
        </div>
      </div>

      <!-- Add to Cart -->
      <div class="cursor-pointer flex items-center">
        <button
          @click="addToCart(book)"
          :disabled="book.stock === 0"
          class="cursor-pointer w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition-all duration-300 delay-75 ease-in-out"
        >
          <CartPlusIcon class="text-white" />
          Keranjang
        </button>
      </div>
    </div>
  </div>
</template>

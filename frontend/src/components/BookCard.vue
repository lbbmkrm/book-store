<script setup>
import { defineProps } from 'vue'
import axios from 'axios'
import CartPlusIcon from '@/components/icons/cart-plus-icon.vue'
import { useToast } from 'vue-toastification'

const apiUrl = import.meta.env.VITE_API_SERVER
const toast = useToast()
const mockBook = '/assets/img/mock-book.jpg'
const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
})
const emits = defineEmits(['update-book'])
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}

const addToCart = async (bookId) => {
  try {
    const response = await axios.post(
      `${apiUrl}/cart`,
      {
        book_id: bookId,
        quantity: 1,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        },
      },
    )
    toast.success(response.data.message, {
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
      icon: CartPlusIcon,
      rtl: false,
    })
    console.log('Berhasil menambahkan buku ke cart')
    emits('update-book', bookId)
  } catch (error) {
    console.error(error.response.data.message)
    toast.error('Gagal menambahkan buku ke cart, coba lagi...', {
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
      icon: CartPlusIcon,
      rtl: false,
    })
  }
}
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg font-label overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 text-gray-900 dark:text-gray-100"
  >
    <!-- Book Image -->
    <div class="relative h-64 flex items-center justify-center">
      <img :src="mockBook" :alt="book.title" class="w-full h-full object-cover" />

      <!-- Category Badge -->
      <div class="absolute top-3 left-3">
        <span
          class="bg-white/90 dark:bg-gray-900/70 text-gray-800 dark:text-gray-100 px-2 py-1 rounded-full text-xs font-medium"
        >
          {{ book.category.name }}
        </span>
      </div>

      <!-- Stock Status -->
      <div class="absolute bottom-3 right-3">
        <span
          class="px-2 py-1 rounded-full text-xs font-medium"
          :class="
            book.stock > 0
              ? 'bg-green-100 text-green-800 dark:bg-green-300/20 dark:text-green-400'
              : 'bg-red-100 text-red-800 dark:bg-red-300/20 dark:text-red-400'
          "
        >
          {{ book.stock > 0 ? `Stok: ${book.stock}` : 'Habis' }}
        </span>
      </div>
    </div>

    <!-- Book Details -->
    <div class="p-6">
      <h3 class="text-lg font-semibold mb-2 min-h-14 font-body">
        {{ book.title }}
      </h3>

      <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
        oleh <span class="font-medium">{{ book.author }}</span>
      </p>

      <p class="text-sm text-gray-700 dark:text-gray-400 mb-4">
        {{ book.description }}
      </p>

      <!-- Price -->
      <div class="flex items-center justify-between mb-4">
        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
          Rp {{ formatPrice(book.price) }}
        </span>
      </div>

      <!-- Add to Cart -->
      <div class="cursor-pointer flex items-center">
        <button
          v-on:click="addToCart(book.id)"
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

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import CartPlusIcon from './icons/cart-plus-icon.vue'

const books = ref([])
async function fetchData() {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/top-books', {
      headers: {
        'Content-Type': 'application/json',
      },
    })
    books.value = res.data.data
  } catch (error) {
    console.error('Error', error.message)
  }
}

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(parseFloat(price))
}

const addToCart = (book) => {
  const existingItem = cartItems.value.find((item) => item.id === book.id)

  if (existingItem) {
    existingItem.quantity += 1
  } else {
    cartItems.value.push({
      ...book,
      quantity: 1,
    })
  }
}
onMounted(() => {
  fetchData()
})
</script>
<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Buku Unggulan</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ho">
        <div
          v-for="book in books"
          :key="book.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
        >
          <!-- Book Image -->
          <div
            class="relative h-64 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center"
          >
            <img
              src="/assets/img/mock-book.jpg"
              :alt="book.img"
              class="w-full h-full object-cover"
            />

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
                <span class="text-2xl font-bold text-blue-600">
                  Rp {{ formatPrice(book.price) }}
                </span>
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
      </div>
    </div>
  </div>
</template>

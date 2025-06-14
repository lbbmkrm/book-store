<script setup>
import BookCard from '@/components/BookCard.vue'
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'

const apiUrl = import.meta.env.VITE_API_SERVER
const books = ref([])
const categories = ref([])
const selectedCategory = ref('All')
const isLoading = ref(false)
const errorMessage = ref(null)

const fetchData = async () => {
  try {
    isLoading.value = true
    const bookResponse = await axios.get(`${apiUrl}/books`, {
      headers: { 'Content-Type': 'application/json' },
    })
    books.value = bookResponse.data.data

    const categoryResponse = await axios.get(`${apiUrl}/categories`, {
      headers: { 'Content-Type': 'application/json' },
    })
    categories.value = categoryResponse.data.data
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Gagal mengambil data'
    alert(errorMessage.value)
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

const updateBook = async (bookId) => {
  try {
    const response = await axios.get(`${apiUrl}/books/${bookId}`, {
      headers: {
        'Content-Type': 'application/json',
      },
    })
    const updatedBook = response.data.data
    const bookIndex = books.value.findIndex((book) => book.id === bookId)
    if (bookIndex !== -1) {
      books.value[bookIndex] = updatedBook
    }
  } catch (error) {
    console.log(error.response.data.message)
    alert('Gagal memperbarui data buku')
  }
}

const filteredBooks = computed(() => {
  if (selectedCategory.value === 'All') return books.value
  return books.value.filter((book) => book.category.name === selectedCategory.value)
})

onMounted(() => {
  fetchData()
})
</script>

<template>
  <!-- Book Page -->
  <main class="min-h-screen mt-18 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
    <div
      v-if="isLoading"
      class="fixed inset-0 flex justify-center items-center bg-white dark:bg-gray-900 z-50"
    >
      <div
        class="animate-spin rounded-full h-16 w-16 border-4 border-gray-300 dark:border-gray-700 border-t-indigo-600"
      ></div>
    </div>

    <div v-else class="w-full max-w-7xl mx-auto px-5 py-8 md:px-12 md:flex gap-8">
      <!-- Category Filter -->
      <div class="w-full md:w-1/5 mb-12 flex flex-col gap-4 sticky top-28 self-start z-10">
        <label
          for="category"
          class="font-light text-xl font-header text-gray-800 dark:text-gray-200"
        >
          Categories
        </label>
        <select
          v-model="selectedCategory"
          name="category"
          id="category"
          class="p-2 text-gray-700 dark:text-gray-200 text-xl cursor-pointer rounded-md font-body border border-sky-200 dark:border-gray-600 bg-white dark:bg-gray-800"
        >
          <option value="All" selected>All</option>
          <option v-for="category in categories" :key="category.id" :value="category.name">
            {{ category.name }}
          </option>
        </select>
      </div>

      <!-- Book Grid -->
      <div class="w-full md:w-3/4">
        <div
          v-if="filteredBooks.length > 0"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <BookCard
            v-for="book in filteredBooks"
            :key="book.id"
            :book="book"
            v-on:update-book="updateBook"
          />
        </div>
        <div v-else class="text-center text-gray-500 dark:text-gray-400 mt-12 font-label">
          Tidak ada buku yang tersedia
        </div>
      </div>
    </div>
  </main>
</template>

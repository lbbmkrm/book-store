<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import BookCard from '@/components/BookCard.vue'

const books = ref([])
const categories = ref([])
const selectedCategory = ref('all')

async function fetchBooks() {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/books', {
      headers: {
        'Content-Type': 'application/json',
      },
    })
    books.value = res.data.data
  } catch (err) {
    console.error('Error fetching books:', err.message)
  }
}

async function fetchCategories() {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/categories', {
      headers: {
        'Content-Type': 'application/json',
      },
    })
    categories.value = res.data.data
  } catch (error) {
    console.error('Error fetching categories:', error.message)
  }
}

const filteredBooks = computed(() => {
  if (selectedCategory.value === 'all') {
    return books.value
  }
  return books.value.filter((book) => book.category.name === selectedCategory.value)
})
const isLoading = computed(() => {
  return books.value.length === 0 ? 'hidden' : ''
})
onMounted(() => {
  fetchBooks()
  fetchCategories()
})
</script>

<template>
  <main class="min-h-screen mt-24">
    <!-- content -->
    <div class="px-5 mx-auto md:px-12 md:py-6 md:flex gap-8">
      <!-- Category -->
      <div :class="isLoading" class="w-[90%] md:w-1/4 mx-auto mb-12 flex flex-col gap-4">
        <label for="category" class="font-light text-xl text-gray-800 font-serif">Categories</label>
        <select
          v-model="selectedCategory"
          name="category"
          id="category"
          class="p-2 text-gray-700 text-xl cursor-pointer rounded-md font-mono border border-sky-200"
        >
          <option value="all" selected>All</option>
          <option
            v-if="categories.length"
            v-for="category in categories"
            :key="category.id"
            :value="category.name"
          >
            {{ category.name }}
          </option>
          <option v-else disabled>Loading categories...</option>
        </select>
      </div>
      <!-- Book Card -->
      <div
        v-if="filteredBooks.length"
        class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
      >
        <BookCard v-for="book in filteredBooks" :key="book.id" :book="book" />
      </div>
      <div v-else class="text-center text-gray-500">
        {{
          filteredBooks.length === 0 && books.length
            ? 'No books found for this category'
            : 'Loading books...'
        }}
      </div>
    </div>
  </main>
</template>

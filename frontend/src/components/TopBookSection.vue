<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import BookCard from './BookCard.vue'

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
onMounted(() => {
  fetchData()
})
</script>
<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-semibold text-gray-900 mb-8 font-serif">Buku Unggulan</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ho">
        <div v-for="book in books" :key="book.id">
          <BookCard :book="book" />
        </div>
      </div>
    </div>
  </div>
</template>

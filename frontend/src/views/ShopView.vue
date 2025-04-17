<script setup>
import CartPlusIcon from '@/components/icons/cart-plus-icon.vue'
import { ref, onMounted, computed } from 'vue'

const books = ref([])
const categories = ref([])

const selectedCategory = ref('all')

async function fetchBooks() {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/products')
    const responseObject = await response.json()
    books.value = responseObject.data
  } catch (err) {
    console.error('Error fetching books:', err.message)
  }
}

async function fetchCategories() {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/categories')
    const resultObject = await res.json()
    categories.value = resultObject.data
    console.log('Categories:', categories.value)
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

const mockBook = ref('/assets/img/mock-book.jpg')

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
      <div class="w-[90%] md:w-1/4 mx-auto mb-12 flex flex-col gap-4">
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
        <div
          v-for="book in filteredBooks"
          :key="book.id"
          class="w-full rounded-lg shadow-xl md:p-2"
        >
          <img
            :src="mockBook"
            alt="Book cover"
            class="w-full h-[250px] mb-4 object-cover rounded"
          />
          <div class="px-4 w-full">
            <p
              class="w-full flex justify-end mb-2 text-sm font-semibold text-indigo-500 font-serif"
            >
              {{ book.category.name }}
            </p>
            <h4 class="font-bold text-xl text-gray-800">{{ book.title }}</h4>
            <p class="text-sm text-gray-500 mb-1">{{ book.author }}</p>
            <div class="w-full flex justify-between mb-4">
              <div>
                <p class="font-light text-emerald-600">
                  Rp. {{ Number(book.price).toLocaleString('id-ID') }}
                </p>
                <p class="text-xs text-gray-400">stock: {{ book.stock }}</p>
              </div>
              <button class="px-4 py-2 cursor-pointer rounded-full hover:bg-sky-50">
                <CartPlusIcon />
              </button>
            </div>
          </div>
          <button
            class="w-full py-1 cursor-pointer bg-indigo-800 text-white rounded-lg transition-all hover:bg-indigo-200 hover:text-indigo-800 delay-100 duration-300 ease-in-out"
          >
            Buy
          </button>
        </div>
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

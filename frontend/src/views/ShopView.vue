<script setup>
import CartPlusIcon from '@/components/icons/cart-plus-icon.vue'
import { ref } from 'vue'
const books = ref([
  {
    id: 1,
    title: 'Book A',
    category: 'Fiction',
    author: 'John Doe',
    desc: 'Example Description about this book',
    price: 1000000,
    stock: 10,
  },
  {
    id: 1,
    title: 'Book A',
    category: 'Fiction',
    author: 'John Doe',
    desc: 'Example Description about this book',
    price: 1000000,
    stock: 10,
  },
  {
    id: 1,
    title: 'Book A',
    category: 'Fiction',
    author: 'John Doe',
    desc: 'Example Description about this book',
    price: 1000000,
    stock: 10,
  },
  {
    id: 1,
    title: 'Book A',
    category: 'Fiction',
    author: 'John Doe',
    desc: 'Example Description about this book',
    price: 1000000,
    stock: 10,
  },
  {
    id: 1,
    title: 'Book A',
    category: 'Fiction',
    author: 'John Doe',
    desc: 'Example Description about this book',
    price: 1000000,
    stock: 10,
  },
])
</script>
<template>
  <main class="min-h-screen mt-24">
    <!-- content -->
    <div class="px-5 mx-auto md:px-12 md:py-6 md:flex gap-8">
      <!-- Category -->
      <div class="w-[90%] md:w-1/4 mx-auto mb-12 flex flex-col gap-4">
        <label for="category" class="font-light text-xl text-gray-800 font-serif">Categories</label>
        <select
          name="category"
          id="category"
          class="p-2 text-gray-700 text-xl cursor-pointer rounded-md font-mono border border-sky-200"
        >
          <option value="all" selected>All</option>
          <option value="fiction">Fiction</option>
          <option value="nonFiction">NonFiction</option>
          <option value="education">Education</option>
          <option value="technology">Technology</option>
        </select>
      </div>
      <!-- Book Card -->
      <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="book in books" key="id" class="w-full rounded-lg shadow-xl md:p-2">
          <img
            src="/assets/img/book-money-and-milk.jpg"
            alt=""
            class="w-full h-[250px] mb-4 object-cover rounded"
          />
          <div class="px-4 w-full">
            <p
              class="w-full flex justify-end mb-2 text-sm font-semibold text-indigo-500 font-serif"
            >
              {{ book.category }}
            </p>
            <h4 class="font-bold text-xl text-gray-800">{{ book.title }}</h4>
            <p class="text-sm text-gray-500 mb-1">
              {{ book.author }}
            </p>
            <div class="w-full flex justify-between mb-4">
              <div>
                <p class="font-light text-emerald-600">{{ book.price }}</p>
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
    </div>
  </main>
</template>

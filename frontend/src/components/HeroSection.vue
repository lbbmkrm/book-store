<template>
  <section class="bg-white mx-auto min-h-screen md:w-[85%]">
    <div class="container p-8 md:py-12 px-4 flex flex-col md:flex-row">
      <!-- Konten kiri -->
      <div class="h-screen w-full md:w-1/2 flex flex-col justify-center space-y-8">
        <h1 class="font-serif font-bold mb-12 text-4xl md:text-5xl leading-tight text-gray-900">
          Buku Pilihan untuk Memperkaya Hidup Anda
        </h1>
        <p class="text-lg md:text-xl text-gray-600">
          Koleksi buku berkualitas tinggi dengan harga terjangkau. Temukan inspirasi dan pengetahuan
          baru dalam setiap halaman.
        </p>
        <div class="flex gap-3">
          <RouterLink
            to="/shop"
            class="px-6 py-3 cursor-pointer shadow-md bg-indigo-700 text-white rounded hover:bg-indigo-500"
          >
            Jelajahi Koleksi
          </RouterLink>
          <RouterLink
            to="#topBooks"
            class="px-6 py-3 cursor-pointer shadow-md bg-slate-50 text-gray-800 rounded hover:bg-slate-200"
          >
            Buku Terbaik
          </RouterLink>
        </div>
      </div>
      <!-- Konten kanan -->
      <div class="w-full md:w-1/2">
        <img
          :src="heroImage"
          alt="Gambar Hero"
          class="h-screen w-full md:h-[90vh] rounded mx-auto shadow-lg"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'

const heroImage = ref('/assets/img/hero.jpg')
</script>

<style scoped>
/* Gaya tambahan jika diperlukan */
</style>

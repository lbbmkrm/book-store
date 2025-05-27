<template>
  <section class="min-h-screen flex mb-8 md:mb-0">
    <div class="container flex flex-col w-[85%] md:w-[75%] mx-auto gap-8 self-center">
      <div class="text-center md:mb-8">
        <h3 class="text-3xl font-serif font-bold text-gray-800">Mengapa Memilih Kami</h3>
        <p class="text-sm text-gray-500">Keunggulan yang membuat kami berbeda</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="feature in features"
          :key="feature.title"
          class="p-4 flex flex-col text-center gap-2"
        >
          <div class="text-4xl">{{ feature.icon }}</div>
          <h3 class="text-xl font-semibold text-gray-800">{{ feature.title }}</h3>
          <p class="text-gray-600">{{ feature.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const features = ref([
  {
    icon: '📦',
    title: 'Pengiriman Cepat',
    description: 'Buku sampai di tangan Anda dalam waktu 1-3 hari kerja ke seluruh Indonesia.',
  },
  {
    icon: '✅',
    title: 'Buku Original',
    description: 'Semua buku yang kami jual adalah asli dengan kualitas terbaik.',
  },
  {
    icon: '💰',
    title: 'Harga Bersaing',
    description: 'Dapatkan buku berkualitas dengan harga terbaik dan diskon menarik.',
  },
  {
    icon: '🎁',
    title: 'Program Loyalitas',
    description: 'Kumpulkan poin dari setiap pembelian untuk ditukarkan dengan hadiah.',
  },
])
</script>

<style scoped>
/* Gaya tambahan jika diperlukan */
</style>

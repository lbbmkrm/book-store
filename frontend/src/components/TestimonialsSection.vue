<script setup>
import { ref } from 'vue'
const testimonials = ref([
  {
    name: 'Budi Santoso',
    role: 'Mahasiswa',
    avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
    text: 'Buku yang saya pesan selalu dalam kondisi baik dan pengirimannya cepat. Koleksinya lengkap dan harganya terjangkau!',
  },
  {
    name: 'Siti Nurhaliza',
    role: 'Guru',
    avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
    text: 'Sebagai guru, saya sering membeli buku referensi di sini. Pilihan bukunya berkualitas dan sangat membantu untuk mengajar.',
  },
  {
    name: 'Reza Rahadian',
    role: 'Profesional',
    avatar: 'https://randomuser.me/api/portraits/men/67.jpg',
    text: 'Layanan pelanggan yang responsif dan profesional. Saya sudah berlangganan selama 2 tahun dan tidak pernah kecewa.',
  },
])
</script>
<template>
  <section class="mb-8 md:mb-24 flex mt-24 md:mt-0">
    <div class="container w-[90%] md:w-[75%] mx-auto">
      <div class="text-center mb-8">
        <h3 class="text-2xl font-bold text-gray-800 font-serif">Apa Kata Pembaca</h3>
        <p class="text-md text-gray-500">Pengalaman mereka dengan koleksi buku kami</p>
      </div>
      <div class="cart-review grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
          v-for="testimonial in testimonials"
          :key="testimonials.name"
          class="p-8 mx-auto shadow-xl rounded-xl"
        >
          <p class="text-light mb-4 text-gray-600 italic">"{{ testimonial.text }}"</p>
          <div class="flex item-center">
            <img
              :src="testimonial.avatar"
              :alt="testimonial.name"
              class="rounded-full w-12 h-12 object-cover mr-4"
            />
            <div>
              <h5 class="text-gray-800 text-serif font-semibold">{{ testimonial.name }}</h5>
              <p class="text-gray-600">{{ testimonial.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

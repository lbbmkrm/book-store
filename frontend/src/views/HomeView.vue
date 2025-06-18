<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import axios from 'axios'
import BookCard from '@/components/BookCard.vue'

const apiUrl = import.meta.env.VITE_API_SERVER
const heroImage = ref('/assets/img/hero-section-book.jpg')
const topBooks = ref([])
const isLoading = ref(false)
const error = ref(null)

async function fetchTopBooks() {
  isLoading.value = true
  error.value = null
  try {
    const res = await axios.get(`${apiUrl}/top-books`, {
      headers: {
        'Content-Type': 'application/json',
      },
    })
    topBooks.value = res.data.data || []
  } catch (err) {
    console.error('Error fetching books:', err.message)
    error.value = 'Gagal memuat buku. Silakan coba lagi.'
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
    const bookIndex = topBooks.value.findIndex((book) => {
      return book.id === bookId
    })
    if (bookIndex !== -1) {
      topBooks.value[bookIndex] = updatedBook
    }
  } catch (error) {
    console.log(error.response.data.message)
  }
}

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
])

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

onMounted(() => {
  fetchTopBooks()
})
</script>

<template>
  <main class="dark:bg-gray-800 dark:text-gray-100">
    <!-- Hero Section -->
    <section class="bg-white mt-18 dark:bg-gray-800 min-h-screen">
      <div class="container mx-auto px-6 py-12 min-h-screen flex items-center">
        <div class="flex flex-col lg:flex-row gap-12 items-center w-full max-w-6xl mx-auto">
          <!-- Konten kiri -->
          <div class="flex-1 space-y-8">
            <div class="space-y-6">
              <h1
                class="font-header text-4xl lg:text-6xl font-bold text-gray-900 dark:text-gray-100 leading-tight"
              >
                Temukan Buku
                <span class="text-indigo-600 dark:text-indigo-400">Terbaik</span>
                untuk Anda
              </h1>
            </div>

            <p class="font-body text-lg text-gray-600 dark:text-gray-300 leading-relaxed max-w-xl">
              Pilihan buku yang menginspirasi, mencerdaskan, dan tak lekang oleh waktu.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 font-label">
              <RouterLink
                to="/shop"
                class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 shadow-lg transition-colors duration-200"
              >
                Jelajahi Koleksi
              </RouterLink>

              <RouterLink
                to="#topBooks"
                class="px-8 py-3 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 shadow-lg transition-colors duration-200"
              >
                Buku Terbaik
              </RouterLink>
            </div>

            <!-- Statistik -->
            <div class="flex space-x-8 pt-6 border-t border-gray-200 dark:border-gray-700">
              <div>
                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">1000+</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Buku Tersedia</div>
              </div>
              <div>
                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">50K+</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Pembaca Puas</div>
              </div>
              <div>
                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">4.9★</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Rating</div>
              </div>
            </div>
          </div>

          <!-- Konten kanan -->
          <div class="flex-1 flex justify-center lg:justify-end">
            <div class="relative">
              <img
                :src="heroImage"
                alt="Koleksi Buku Premium"
                class="w-full max-w-md h-[400px] lg:h-[500px] object-cover rounded-2xl shadow-xl"
              />
              <div
                class="absolute -bottom-4 -right-4 w-full h-full bg-indigo-100 dark:bg-indigo-900 rounded-2xl -z-10"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Top Books Section -->
    <section id="topBooks" class="py-16 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-header mb-4">
            Buku Unggulan
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400">
            Koleksi buku terbaik pilihan editor
          </p>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
          <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat buku...</span>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 max-w-md mx-auto"
          >
            <p class="text-red-600 dark:text-red-400 mb-4">{{ error }}</p>
            <button
              @click="fetchTopBooks"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              Coba Lagi
            </button>
          </div>
        </div>

        <!-- Books Grid -->
        <div v-else-if="topBooks.length > 0" class="flex flex-wrap justify-center gap-6">
          <div
            v-for="book in topBooks"
            :key="book.id"
            class="flex-shrink w-full sm:w-1/2 lg:w-[45%] xl:w-1/4 max-w-sm"
            v-on:update-book="updateBook"
          >
            <BookCard :book="book" />
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400">Belum ada buku yang tersedia saat ini.</p>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white dark:bg-gray-800 transition-colors duration-300">
      <div class="container mx-auto px-6">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 font-header mb-4">
            Mengapa Memilih Kami
          </h2>
          <p class="text-lg text-gray-500 dark:text-gray-400">
            Keunggulan yang membuat kami berbeda
          </p>
        </div>

        <div class="flex flex-wrap justify-center gap-8 max-w-6xl mx-auto">
          <div
            v-for="feature in features"
            :key="feature.title"
            class="flex-1 min-w-64 max-w-sm p-6 flex flex-col items-center text-center bg-gray-50 dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
          >
            <div class="text-4xl mb-4">{{ feature.icon }}</div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3 font-header">
              {{ feature.title }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300 font-body leading-relaxed">
              {{ feature.description }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
      <div class="container mx-auto px-6">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 font-header mb-4">
            Apa Kata Pembaca
          </h2>
          <p class="text-lg text-gray-500 dark:text-gray-400 font-body">
            Pengalaman mereka dengan koleksi buku kami
          </p>
        </div>

        <div class="flex flex-wrap justify-center gap-8 max-w-6xl mx-auto">
          <div
            v-for="testimonial in testimonials"
            :key="testimonial.name"
            class="flex-1 min-w-80 max-w-sm p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300"
          >
            <div class="mb-6">
              <svg class="w-8 h-8 text-indigo-500 mb-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14,17H17L19,13V7H13V13H16M6,17H9L11,13V7H5V13H8L6,17Z" />
              </svg>
              <p class="text-gray-600 dark:text-gray-300 italic font-body leading-relaxed">
                {{ testimonial.text }}
              </p>
            </div>

            <div class="flex items-center">
              <img
                :src="testimonial.avatar"
                :alt="testimonial.name"
                class="rounded-full w-12 h-12 object-cover mr-4 border-2 border-indigo-500"
              />
              <div>
                <h4 class="text-gray-800 dark:text-gray-100 font-semibold font-header">
                  {{ testimonial.name }}
                </h4>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ testimonial.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

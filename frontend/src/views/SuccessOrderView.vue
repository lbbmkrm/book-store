<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// State
const order = JSON.parse(localStorage.getItem('order'))
const loading = ref(false)
const error = ref(null)

// Format price
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(price)
}

// Format date
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

// Get status badge class
const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'processing':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
    case 'completed':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
    case 'failed':
      return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
  }
}

// Get status text
const getStatusText = (status) => {
  switch (status) {
    case 'processing':
      return 'Sedang Diproses'
    case 'completed':
      return 'Selesai'
    case 'failed':
      return 'Gagal'
    default:
      return status
  }
}

// Navigate to orders list
const goToOrders = () => {
  router.push('/orders')
}

// Continue shopping
const continueShopping = () => {
  router.push('/shop')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8 font-label dark:bg-gray-800 dark:text-gray-200">
    <div class="container mx-auto px-4 max-w-4xl">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto dark:border-gray-200"
        ></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat detail pesanan...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div
          class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center dark:bg-red-900"
        >
          <svg
            class="w-8 h-8 text-red-600 dark:text-red-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ error }}</h2>
        <button
          @click="goToOrders"
          class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
        >
          Lihat Semua Pesanan
        </button>
      </div>

      <!-- Success State -->
      <div v-else-if="order" class="space-y-8">
        <!-- Success Header -->
        <div class="text-center py-8">
          <div
            class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center dark:bg-green-900"
          >
            <svg
              class="w-10 h-10 text-green-600 dark:text-green-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
              ></path>
            </svg>
          </div>
          <h1 class="text-3xl font-bold font-header text-gray-900 dark:text-gray-100 mb-2">
            Pesanan Berhasil Dibuat!
          </h1>
          <p class="text-gray-600 dark:text-gray-400 mb-4">
            Terima kasih telah berbelanja. Pesanan Anda sedang diproses.
          </p>
          <div class="inline-flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">ID Pesanan:</span>
            <span class="font-mono text-lg font-semibold text-blue-600 dark:text-blue-400">
              #{{ String(order.id).padStart(6, '0') }}
            </span>
          </div>
        </div>

        <!-- Order Details -->
        <div
          class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden dark:bg-gray-800 dark:border-gray-700"
        >
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Detail Pesanan</h2>
              <span
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                :class="getStatusBadgeClass(order.status)"
              >
                {{ getStatusText(order.status) }}
              </span>
            </div>
          </div>

          <div class="p-6 space-y-6">
            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                  Tanggal Pesanan
                </h3>
                <p class="text-gray-900 dark:text-gray-100">
                  {{ formatDate(order.createdAt) }}
                </p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                  Total Pembayaran
                </h3>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                  {{ formatPrice(order.totalPrice) }}
                </p>
              </div>
            </div>

            <!-- Shipping Address -->
            <div>
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                Alamat Pengiriman
              </h3>
              <div class="bg-gray-50 rounded-md p-4 dark:bg-gray-700">
                <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">
                  {{ order.shippingAddress }}
                </p>
              </div>
            </div>

            <!-- Order Items -->
            <div>
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">
                Item Pesanan
              </h3>
              <div class="space-y-4">
                <div
                  v-for="item in order.orderDetail"
                  :key="item.id"
                  class="flex items-start gap-4 p-4 bg-gray-50 rounded-md dark:bg-gray-700"
                >
                  <div class="w-16 h-20 flex-shrink-0">
                    <img
                      :src="
                        item.book?.img
                          ? `/storage/${item.book.img}`
                          : 'http://127.0.0.1:8000/storage/images/mock-book.jpg'
                      "
                      :alt="item.book?.title || 'Book'"
                      class="w-full h-full object-cover rounded border border-gray-200 dark:border-gray-600"
                    />
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                      {{ item.book?.title || 'Produk tidak tersedia' }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ item.quantity }} × {{ formatPrice(item.price) }}
                    </p>
                  </div>
                  <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ formatPrice(item.price * item.quantity) }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Payment Method -->
            <div>
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                Metode Pembayaran
              </h3>
              <div class="flex items-center bg-gray-50 rounded-md p-4 dark:bg-gray-700">
                <svg
                  class="w-5 h-5 text-green-600 mr-3 dark:text-green-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
                <span class="text-gray-900 dark:text-gray-100"> Bayar di Tempat (COD) </span>
              </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">
                Ringkasan Pembayaran
              </h3>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                  <span class="text-gray-900 dark:text-gray-100">{{
                    formatPrice(order.totalPrice)
                  }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                  <span class="text-gray-900 dark:text-gray-100">Gratis</span>
                </div>
                <div
                  class="flex justify-between text-sm border-t border-gray-200 dark:border-gray-600 pt-2"
                >
                  <span class="font-semibold text-gray-900 dark:text-gray-100">Total</span>
                  <span class="font-semibold text-xl text-gray-900 dark:text-gray-100">{{
                    formatPrice(order.totalPrice)
                  }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Next Steps -->
        <div
          class="bg-blue-50 border border-blue-200 rounded-lg p-6 dark:bg-blue-900/20 dark:border-blue-800"
        >
          <div class="flex items-start">
            <svg
              class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-1 mr-3 flex-shrink-0"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              ></path>
            </svg>
            <div>
              <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">
                Langkah Selanjutnya
              </h3>
              <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                <li>• Pesanan Anda akan diproses dalam 1-2 hari kerja</li>
                <li>• Anda akan menerima notifikasi ketika pesanan dikirim</li>
                <li>• Siapkan uang tunai untuk pembayaran COD</li>
                <li>• Pastikan alamat pengiriman dapat dijangkau kurir</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
            @click="goToOrders"
            class="px-6 py-3 bg-gray-600 text-white rounded-md cursor-pointer hover:bg-gray-700 transition-colors font-medium"
          >
            Lihat Semua Pesanan
          </button>
          <button
            @click="continueShopping"
            class="px-6 py-3 bg-blue-600 text-white rounded-md cursor-pointer hover:bg-blue-700 transition-colors font-medium"
          >
            Lanjutkan Belanja
          </button>
        </div>

        <!-- Contact Support -->
        <div class="text-center py-4 border-t border-gray-200 dark:border-gray-600">
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            Ada pertanyaan tentang pesanan Anda?
          </p>
          <a
            href="mailto:support@bookstore.com"
            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-sm"
          >
            Hubungi Customer Service
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

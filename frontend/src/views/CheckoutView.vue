<script setup>
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import axios from 'axios'
const router = useRouter()
const userProfile = ref(JSON.parse(localStorage.getItem('user')) || {})
const cartItems = localStorage.getItem('cartItems')
  ? JSON.parse(localStorage.getItem('cartItems'))
  : []
const subTotal = localStorage.getItem('subtotal') || 0
const shipping = localStorage.getItem('shipping') || 0
const total = localStorage.getItem('total') || 0
const address = ref('')
const createOrder = async () => {
  try {
    const response = await axios.post(
      'http://localhost:8000/api/orders',
      {
        shipping_address: address.value,
      },
      {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        },
      },
    )
    if (response.status === 201) {
      localStorage.removeItem('cartItems')
      localStorage.removeItem('subtotal')
      localStorage.removeItem('shipping')
      localStorage.removeItem('total')
      localStorage.setItem('order', JSON.stringify(response.data.data))

      router.push({
        name: 'SuccessOrder',
      })
    }
  } catch (error) {
    console.log(error)
    alert('Gagal membuat pesanan. Silakan coba lagi.')
  }
}
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(price)
}
</script>

<template>
  <div class="min-h-screen mt-18 bg-gray-50 py-8 font-label dark:bg-gray-800 dark:text-gray-200">
    <!-- Main Content -->
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="mb-8">
        <RouterLink
          to="/cart"
          class="cursor-pointer flex items-center text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 mb-4 transition-colors"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
          Kembali ke Keranjang
        </RouterLink>
        <h1 class="text-3xl font-bold font-header text-gray-900 dark:text-gray-100">Checkout</h1>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="mb-6">
        <div
          class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-200"
        >
          <span class="block sm:inline">{{ error }}</span>
          <button @click="clearError" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <span class="sr-only">Tutup</span>×
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700"
          >
            <h2 class="text-xl font-semibold mb-6 dark:text-gray-100">Informasi Pengiriman</h2>

            <form @submit.prevent="submitOrder" class="space-y-6">
              <!-- User Info (Read-only) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Penerima
                  </label>
                  <input
                    type="text"
                    :value="userProfile.name"
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nomor Telepon
                  </label>
                  <input
                    type="text"
                    :value="userProfile.phone"
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                  />
                </div>
              </div>

              <!-- Shipping Address -->
              <div>
                <label
                  for="shipping_address"
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                  Alamat Pengiriman Lengkap *
                </label>
                <textarea
                  v-model="address"
                  id="shipping_address"
                  rows="4"
                  placeholder="Masukkan alamat lengkap termasuk nama jalan, nomor rumah, kecamatan, kota, provinsi, dan kode pos"
                  class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                  required
                ></textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 255 karakter</p>
              </div>

              <!-- Payment Method (Static) -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Metode Pembayaran
                </label>
                <div
                  class="bg-gray-50 border border-gray-200 rounded-md p-4 dark:bg-gray-700 dark:border-gray-600"
                >
                  <div class="flex items-center">
                    <svg
                      class="w-5 h-5 text-green-600 mr-3"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                      Bayar di Tempat (COD)
                    </span>
                  </div>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">
                    Pembayaran dilakukan saat barang diterima
                  </p>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700 sticky top-8"
          >
            <h2 class="text-xl font-semibold mb-4 dark:text-gray-100">Ringkasan Pesanan</h2>

            <!-- Items -->
            <div class="space-y-3 mb-4">
              <div v-for="item in cartItems" :key="item.id" class="flex items-start gap-3">
                <div class="w-12 h-16 flex-shrink-0">
                  <img
                    :src="
                      item.book.img
                        ? `/storage/${item.book.img}`
                        : 'http://127.0.0.1:8000/storage/images/mock-book.jpg'
                    "
                    :alt="item.book.title"
                    class="w-full h-full object-cover rounded border border-gray-200 dark:border-gray-600"
                  />
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                    {{ item.book.title }}
                  </h4>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ item.quantity }} × {{ formatPrice(item.book.price) }}
                  </p>
                </div>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                  {{ formatPrice(item.book.price * item.quantity) }}
                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400"
                  >Subtotal ({{ cartItems.length }} item)</span
                >
                <span class="text-gray-900 dark:text-gray-100">{{ formatPrice(subTotal) }}</span>
              </div>

              <div class="flex justify-between text-sm">
                <span class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                  Ongkir
                  <span v-if="shipping === 0" class="text-xs text-green-600 dark:text-green-400"
                    >(Gratis)</span
                  >
                </span>
                <span class="text-gray-900 dark:text-gray-100">{{ formatPrice(shipping) }}</span>
              </div>

              <div
                class="flex justify-between text-lg font-semibold pt-3 border-t border-gray-200 dark:border-gray-600"
              >
                <span class="text-gray-900 dark:text-gray-100">Total</span>
                <span class="text-gray-900 dark:text-gray-100">{{ formatPrice(total) }}</span>
              </div>
            </div>

            <!-- Submit Button -->
            <button
              v-on:click="createOrder"
              :disabled="!address.trim()"
              class="cursor-pointer w-full mt-6 py-3 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:focus:ring-offset-gray-800"
            >
              <span> Buat Pesanan </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

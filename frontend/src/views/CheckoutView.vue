<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import CheckFillIcon from '@/components/icons/check-fill-icon.vue'
import { useCartStore } from '@/stores/cart'

const router = useRouter()
const apiUrl = import.meta.env.VITE_API_SERVER

const error = ref(null)

const userProfile = ref(JSON.parse(localStorage.getItem('user')) || {})
const cartStore = useCartStore()
const toast = useToast()
const address = ref('')

const createOrder = async () => {
  try {
    await axios.post(
      `${apiUrl}/orders`,
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
    router.push('/successOrder')
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal membuat pesanan. Silakan coba lagi.'
    toast.error('Gagal membuat pesanan')
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8 font-label dark:bg-gray-800 dark:text-gray-200">
    <div class="container mx-auto px-4">
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

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700"
          >
            <h2 class="text-xl font-semibold mb-6 dark:text-gray-100">Informasi Pengiriman</h2>
            <form @submit.prevent="createOrder" class="space-y-6">
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

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Metode Pembayaran
                </label>
                <div
                  class="bg-gray-50 border border-gray-200 rounded-md p-4 dark:bg-gray-700 dark:border-gray-600"
                >
                  <div class="flex items-center gap-2">
                    <CheckFillIcon class="w-4 h-4 text-green-600 rounded-full dark:bg-white" />
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                      Bayar di Tempat (COD)
                    </span>
                  </div>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-8">
                    Pembayaran dilakukan saat barang diterima
                  </p>
                </div>
              </div>

              <!-- PERBAIKAN: pindahkan tombol ke dalam form dan ganti ke type="submit" -->
              <button
                type="submit"
                :disabled="!address.trim()"
                class="cursor-pointer w-full mt-6 py-3 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:focus:ring-offset-gray-800"
              >
                <span> Buat Pesanan </span>
              </button>
            </form>
          </div>
        </div>

        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700 sticky top-8"
          >
            <h2 class="text-xl font-semibold mb-4 dark:text-gray-100">Ringkasan Pesanan</h2>

            <div class="space-y-3 mb-4">
              <div
                v-for="item in cartStore.cartItems"
                :key="item.id"
                class="flex items-start gap-3"
              >
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
                    {{ item.quantity }} × {{ cartStore.shippingCost }}
                  </p>
                </div>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                  {{ cartStore.total }}
                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400"
                  >Subtotal ({{ cartStore.cartItems.length }} item)</span
                >
                <span class="text-gray-900 dark:text-gray-100">{{ cartStore.subTotal }}</span>
              </div>

              <div class="flex justify-between text-sm">
                <span class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                  Ongkir
                  <span
                    v-if="cartStore.shippingCost == 0"
                    class="text-xs text-green-600 dark:text-green-400"
                    >(Gratis)</span
                  >
                </span>
                <span class="text-gray-900 dark:text-gray-100">{{ cartStore.shippingCost }}</span>
              </div>

              <div
                class="flex justify-between text-lg font-semibold pt-3 border-t border-gray-200 dark:border-gray-600"
              >
                <span class="text-gray-900 dark:text-gray-100">Total</span>
                <span class="text-gray-900 dark:text-gray-100">{{ cartStore.total }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

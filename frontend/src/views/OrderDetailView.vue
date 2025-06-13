<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const toast = useToast()
const route = useRoute()
const router = useRouter()

const order = ref(null)
const isLoading = ref(false)
const error = ref(null)

async function fetchOrderDetail() {
  isLoading.value = true
  error.value = null
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/orders/${route.params.id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        'Content-Type': 'application/json',
      },
    })
    order.value = res.data.data || null
  } catch (err) {
    console.error('Error fetching order detail:', err.message)
    if (err.response?.data?.message) {
      console.log(err.response.data.message)
    }
    error.value = 'Gagal memuat detail pesanan. Silakan coba lagi.'
  } finally {
    isLoading.value = false
  }
}

async function changeOrderStatus(statusOrder) {
  try {
    const response = await axios.post(
      `http://127.0.0.1:8000/api/orders/${order.value.id}/status`,
      {
        status: statusOrder,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
          'Content-Type': 'application/json',
        },
      },
    )
    toast.success(response.data.message, {
      position: 'top-center',
      timeout: 2000,
      closeOnClick: true,
      pauseOnFocusLoss: true,
      pauseOnHover: false,
      draggable: true,
      draggablePercent: 0.5,
      showCloseButtonOnHover: false,
      hideProgressBar: true,
      closeButton: false,
      icon: true,
      rtl: false,
    })
    await fetchOrderDetail()
  } catch (err) {
    console.error('Error cancelling order:', err.message)
    toast.error('Terjadi Kesalahan, Coba Lagi...', {
      position: 'top-center',
      timeout: 2000,
      closeOnClick: true,
      pauseOnFocusLoss: true,
      pauseOnHover: false,
      draggable: true,
      draggablePercent: 0.5,
      showCloseButtonOnHover: false,
      hideProgressBar: true,
      closeButton: false,
      icon: true,
      rtl: false,
    })
  }
}
onMounted(() => {
  fetchOrderDetail()
})
</script>

<template>
  <main class="dark:bg-gray-800 dark:text-gray-100 min-h-screen font-label">
    <!-- Header Section -->
    <section class="bg-white dark:bg-gray-800 py-12">
      <div class="container mx-auto px-6">
        <div class="flex items-center gap-4 mb-4">
          <button
            @click="router.go(-1)"
            class="cursor-pointer p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
          >
            ← Kembali
          </button>
          <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 font-header">
            Detail Pesanan
          </h1>
        </div>
      </div>
    </section>

    <!-- Order Detail Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
          <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat detail pesanan...</span>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 max-w-md mx-auto"
          >
            <p class="text-red-600 dark:text-red-400 mb-4">{{ error }}</p>
            <button
              @click="fetchOrderDetail"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              Coba Lagi
            </button>
          </div>
        </div>

        <!-- Order Detail Content -->
        <div v-else-if="order" class="space-y-6">
          <!-- Order Info Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold font-body text-gray-800 dark:text-gray-100 mb-2">
                  Pesanan #{{ order.id }}
                </h2>
                <div class="space-y-1">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Tanggal Pesanan:
                    {{
                      new Date(order.createdAt).toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                      })
                    }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Terakhir Diperbarui:
                    {{
                      new Date(order.updatedAt).toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                      })
                    }}
                  </p>
                </div>
              </div>
              <div class="flex flex-col items-end gap-3 mt-4 sm:mt-0">
                <span
                  class="px-4 py-2 rounded text-sm font-semibold bg-cyan-600 text-gray-200 dark:bg-cyan-300 dark:text-gray-800"
                >
                  <p>{{ order.status }}</p>
                </span>
                <div v-if="order.status === 'shipping'" class="flex gap-2">
                  <button
                    v-on:click="changeOrderStatus('completed')"
                    class="cursor-pointer px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200"
                  >
                    Terima Pesanan
                  </button>
                  <button
                    v-on:click="changeOrderStatus('failed')"
                    class="cursor-pointer px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors duration-200"
                  >
                    Batalkan Pesanan
                  </button>
                </div>
              </div>
            </div>

            <!-- Shipping Address -->
            <div
              v-if="order.shippingAddress"
              class="border-t border-gray-200 dark:border-gray-700 pt-4"
            >
              <h3 class="text-lg font-semibold font-body text-gray-800 dark:text-gray-100 mb-2">
                Alamat Pengiriman
              </h3>
              <p class="text-gray-600 dark:text-gray-400">{{ order.shippingAddress }}</p>
            </div>
          </div>

          <!-- Order Items Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold font-body text-gray-800 dark:text-gray-100 mb-4">
              Item Pesanan
            </h3>

            <div v-if="order.orderDetails && order.orderDetails.length > 0" class="space-y-4">
              <div
                v-for="item in order.orderDetails"
                :key="item.id"
                class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
              >
                <!-- Book Image -->
                <div
                  class="w-16 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg mr-4 flex items-center justify-center flex-shrink-0 overflow-hidden"
                >
                  <img
                    v-if="item.book.img"
                    :src="item.book.img"
                    :alt="item.book.title"
                    class="w-full h-full object-cover"
                  />
                  <span v-else class="text-gray-500 dark:text-gray-400 text-xs">Book</span>
                </div>

                <!-- Item Details -->
                <div class="flex-grow">
                  <h4 class="text-gray-800 dark:text-gray-100 font-semibold font-body mb-1">
                    {{ item.book.title }}
                  </h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    Penulis: {{ item.book.author }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    Kategori: {{ item.book.category }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    Jumlah: {{ item.quantity }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Harga Satuan: Rp {{ parseFloat(item.price).toLocaleString('id-ID') }}
                  </p>
                </div>

                <!-- Item Total -->
                <div class="text-right">
                  <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Rp {{ (parseFloat(item.price) * item.quantity).toLocaleString('id-ID') }}
                  </p>
                </div>
              </div>
            </div>

            <div v-else-if="order.orderDetail && order.orderDetail.length > 0" class="space-y-4">
              <div
                v-for="item in order.orderDetail"
                :key="item.id"
                class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
              >
                <!-- Book Image -->
                <div
                  class="w-16 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg mr-4 flex items-center justify-center flex-shrink-0 overflow-hidden"
                >
                  <img
                    :src="
                      item.book.img
                        ? `/storage/${item.book.img}`
                        : 'http://127.0.0.1:8000/storage/images/mock-book.jpg'
                    "
                    :alt="item.book.title"
                    class="w-full h-full object-cover"
                  />
                </div>

                <!-- Item Details -->
                <div class="flex-grow">
                  <h4 class="text-gray-800 dark:text-gray-100 font-semibold font-body mb-1">
                    {{ item.book.title }}
                  </h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    Penulis: {{ item.book.author }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    Kategori: {{ item.book.category }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    Jumlah: {{ item.quantity }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Harga Satuan: Rp {{ parseFloat(item.price).toLocaleString('id-ID') }}
                  </p>
                </div>

                <!-- Item Total -->
                <div class="text-right">
                  <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Rp {{ (parseFloat(item.price) * item.quantity).toLocaleString('id-ID') }}
                  </p>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-8">
              <p class="text-gray-600 dark:text-gray-400">Tidak ada item dalam pesanan ini.</p>
            </div>
          </div>

          <!-- Order Summary Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 font-header mb-4">
              Ringkasan Pesanan
            </h3>

            <div class="space-y-3">
              <div v-if="order.orderDetails && order.orderDetails.length > 0">
                <div
                  v-for="item in order.orderDetails"
                  :key="item.id"
                  class="flex justify-between text-sm"
                >
                  <span class="text-gray-600 dark:text-gray-400">
                    {{ item.book.title }} ({{ item.quantity }}x)
                  </span>
                  <span class="text-gray-800 dark:text-gray-100">
                    Rp {{ (parseFloat(item.price) * item.quantity).toLocaleString('id-ID') }}
                  </span>
                </div>
              </div>

              <div v-else-if="order.orderDetail && order.orderDetail.length > 0">
                <div
                  v-for="item in order.orderDetail"
                  :key="item.id"
                  class="flex justify-between text-sm"
                >
                  <span class="text-gray-600 dark:text-gray-400">
                    {{ item.book.title }} ({{ item.quantity }}x)
                  </span>
                  <span class="text-gray-800 dark:text-gray-100">
                    Rp {{ (parseFloat(item.price) * item.quantity).toLocaleString('id-ID') }}
                  </span>
                </div>
              </div>

              <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                <div class="flex justify-between text-lg font-semibold">
                  <span class="text-gray-800 dark:text-gray-100">Total</span>
                  <span class="text-gray-800 dark:text-gray-100">
                    Rp {{ parseFloat(order.totalPrice).toLocaleString('id-ID') }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <RouterLink
              to="/orders"
              class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-200 text-center"
            >
              Kembali ke Daftar Pesanan
            </RouterLink>
            <div
              v-if="
                order.status === 'pending' ||
                order.status === 'processing' ||
                order.status === 'shipped'
              "
              class="flex gap-4"
            >
              <button
                v-if="order.status === 'shipped'"
                @click="confirmOrder"
                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200"
              >
                Terima Pesanan
              </button>
              <button
                v-if="order.status === 'pending'"
                @click="cancelOrder"
                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors duration-200"
              >
                Batalkan Pesanan
              </button>
            </div>
          </div>
        </div>

        <!-- Not Found State -->
        <div v-else class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400 font-body mb-4">Pesanan tidak ditemukan.</p>
          <RouterLink
            to="/orders"
            class="inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-200"
          >
            Kembali ke Daftar Pesanan
          </RouterLink>
        </div>
      </div>
    </section>
  </main>
</template>

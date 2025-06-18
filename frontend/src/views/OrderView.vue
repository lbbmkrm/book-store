<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const apiUrl = import.meta.env.VITE_API_SERVER
const orders = ref([])
const isLoading = ref(false)
const error = ref(null)

async function fetchOrders() {
  isLoading.value = true
  error.value = null
  try {
    const res = await axios.get(`${apiUrl}/orders`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        'Content-Type': 'application/json',
      },
    })
    orders.value = res.data.data || []
  } catch (err) {
    console.error('Error fetching orders:', err.message)
    console.log(err.response.data.message)
    error.value = 'Gagal memuat pesanan. Silakan coba lagi.'
  } finally {
    isLoading.value = false
  }
}
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}
onMounted(() => {
  fetchOrders()
})
</script>

<template>
  <main class="mt-18 dark:bg-gray-800 dark:text-gray-100 min-h-screen">
    <!-- Header Section -->
    <section class="bg-white dark:bg-gray-800 py-12">
      <div class="container mx-auto px-6">
        <h1
          class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 font-header mb-4"
        >
          Daftar Pesanan Anda
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 font-body leading-relaxed">
          Lihat semua pesanan Anda dan kelola statusnya di sini.
        </p>
      </div>
    </section>

    <!-- Orders Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
          <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat pesanan...</span>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 max-w-md mx-auto"
          >
            <p class="text-red-600 dark:text-red-400 mb-4">{{ error }}</p>
            <button
              @click="fetchOrders"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              Coba Lagi
            </button>
          </div>
        </div>

        <!-- Orders List -->
        <div v-else-if="orders.length > 0" class="space-y-6">
          <div
            v-for="order in orders"
            :key="order.id"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
          >
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
              <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 font-header">
                  Pesanan #{{ order.id }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  Tanggal: {{ new Date(order.createdAt).toLocaleDateString('id-ID') }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  Status:
                  <span
                    :class="{
                      'text-green-600 dark:text-green-400': order.status === 'completed',
                      'text-yellow-600 dark:text-yellow-400': order.status === 'shipping',
                      'text-red-600 dark:text-red-400': order.status === 'failed',
                    }"
                    >{{ order.status }}</span
                  >
                </p>
                <p v-if="order.shippingAddress" class="text-sm text-gray-600 dark:text-gray-400">
                  Alamat Pengiriman: {{ order.shippingAddress }}
                </p>
              </div>
              <div class="flex gap-4 mt-4 sm:mt-0">
                <RouterLink
                  :to="`/orders/${order.id}`"
                  class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-200"
                >
                  Lihat Detail
                </RouterLink>
              </div>
            </div>

            <!-- Order Details Section -->
            <div
              v-if="order.orderDetail && order.orderDetail.length > 0"
              class="border-t border-gray-200 dark:border-gray-700 pt-4"
            >
              <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 font-header mb-3">
                Item Pesanan
              </h4>
              <div v-for="item in order.orderDetail" :key="item.id" class="flex items-center mb-4">
                <!-- Note: Since orderDetail doesn't include book info, you'll need to fetch it separately or include it in the resource -->
                <div
                  class="w-16 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg mr-4 flex items-center justify-center"
                >
                  <span class="text-gray-500 dark:text-gray-400 text-xs">Book</span>
                </div>
                <div>
                  <p class="text-gray-800 dark:text-gray-100 font-semibold font-body">
                    Book ID: {{ item.bookId }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Jumlah: {{ item.quantity }} | Harga: Rp {{ item.price.toLocaleString('id-ID') }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Total Price -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
              <p class="text-right text-lg font-semibold text-gray-800 dark:text-gray-100">
                Total: Rp {{ formatPrice(order.totalPrice) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400 font-body">
            Belum ada pesanan yang tersedia saat ini.
          </p>
          <RouterLink
            to="/shop"
            class="mt-4 inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-200"
          >
            Mulai Belanja
          </RouterLink>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import ThrashIcon from '@/components/icons/thrash-icon.vue'
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import axios from 'axios'

const cartItems = ref([])
const error = ref(null)
const loading = ref(false)

// Fetch cart items from API
const fetchCart = async () => {
  loading.value = true
  try {
    const response = await axios.get('http://localhost:8000/api/cart', {
      headers: {
        'content-type': 'Application/json',
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    })
    cartItems.value = response.data.data.cartDetails
    console.log('Cart items fetched successfully')
  } catch (error) {
    console.error('Error fetching cart items:', error)
    error.value = 'Gagal memuat keranjang belanja. Silakan coba lagi.'
  } finally {
    loading.value = false
  }
}

// Update quantity of cart item
const updateQuantity = async (itemId, change) => {
  const item = cartItems.value.find((item) => item.id === itemId)
  if (!item) return

  const newQuantity = item.quantity + change

  // Validate quantity
  if (newQuantity < 1 || newQuantity > item.book.stock) return

  try {
    const response = await axios.put(
      `http://localhost:8000/api/cart/${itemId}`,
      {
        quantity: newQuantity,
      },
      {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        },
      },
    )

    // Update local state
    item.quantity = newQuantity
    console.log('Quantity updated successfully')
  } catch (error) {
    console.error('Error updating quantity:', error)
    error.value = 'Gagal mengupdate jumlah item. Silakan coba lagi.'
  }
}

// Remove item from cart
const removeItem = async (itemId) => {
  try {
    await axios.delete(`http://localhost:8000/api/cart/${itemId}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    })

    // Remove from local state
    cartItems.value = cartItems.value.filter((item) => item.id !== itemId)
    console.log('Item removed successfully')
  } catch (error) {
    console.error('Error removing item:', error)
    error.value = 'Gagal menghapus item. Silakan coba lagi.'
  }
}

// Format price to Indonesian Rupiah
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(price)
}

// Computed properties for order summary
const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => {
    return total + item.book.price * item.quantity
  }, 0)
})

const shipping = computed(() => {
  // Free shipping if subtotal > 100000, otherwise 15000
  return subtotal.value > 100000 ? 0 : 15000
})

const total = computed(() => {
  return subtotal.value + shipping.value
})

// Clear error message
const clearError = () => {
  error.value = null
}

onMounted(() => {
  fetchCart()
})
</script>

<template class="dark:bg-transparent">
  <div class="min-h-screen mt-18 bg-gray-50 py-8 font-sans dark:bg-gray-800 dark:text-gray-200">
    <!-- Loading State -->
    <div v-if="loading" class="container mx-auto px-4 text-center py-12">
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto dark:border-gray-200"
      ></div>
      <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat keranjang belanja...</p>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="container mx-auto px-4 mb-6">
      <div
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-200"
      >
        <span class="block sm:inline">{{ error }}</span>
        <button @click="clearError" class="absolute top-0 bottom-0 right-0 px-4 py-3">
          <span class="sr-only">Tutup</span>
          ×
        </button>
      </div>
    </div>

    <!-- Cart Content -->
    <div
      v-if="!loading && cartItems.length > 0"
      class="container mx-auto px-4 dark:bg-gray-800 dark:border-gray-700"
    >
      <!-- Header -->
      <div
        class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-700"
      >
        <h1 class="text-2xl font-bold">Keranjang Belanja</h1>
        <span class="text-gray-600 text-sm lg:text-base dark:text-gray-400">
          {{ cartItems.length }} item
        </span>
      </div>

      <!-- Main Layout -->
      <div class="flex flex-col lg:flex-row gap-8 dark:bg-gray-800">
        <!-- Cart Items -->
        <div class="order-1 lg:flex-2 dark:bg-transparent lg:col-span-8">
          <div
            class="bg-white rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700 dark:bg-gray-800"
          >
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
              <!-- Item -->
              <div
                v-for="item in cartItems"
                :key="item.id"
                class="flex items-start gap-4 lg:gap-6 py-4 dark:border-b dark:border-gray-700"
              >
                <div class="w-16 h-20 lg:w-24 lg:h-32 flex-shrink-0">
                  <img
                    :src="item.book.img ? `/storage/${item.book.img}` : '/placeholder-book.jpg'"
                    :alt="item.book.title"
                    class="w-full h-full object-cover rounded border border-gray-200 dark:border-gray-600"
                    @error="$event.target.src = '/placeholder-book.jpg'"
                  />
                </div>

                <div class="flex-1 min-w-0 dark:text-gray-200">
                  <div class="mb-2 lg:mb-3 dark:text-gray-300">
                    <h3
                      class="text-sm lg:text-lg font-medium text-gray-900 mb-1 dark:text-gray-200"
                    >
                      {{ item.book.title }}
                    </h3>
                    <div class="text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                      <div>Penulis: {{ item.book.author }}</div>
                    </div>
                    <div class="text-xs lg:text-sm text-gray-500 space-y-1 dark:text-gray-400">
                      <div>{{ formatPrice(item.book.price) }}</div>
                    </div>
                  </div>

                  <div class="flex items-center justify-between dark:text-gray-300">
                    <div
                      class="flex items-center border border-gray-200 rounded dark:border-gray-600"
                    >
                      <button
                        @click="updateQuantity(item.id, -1)"
                        class="cursor-pointer px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
                        :disabled="item.quantity <= 1"
                        title="Kurangi jumlah"
                      >
                        −
                      </button>
                      <span
                        class="px-3 lg:px-4 py-1 lg:py-2 text-sm border-x border-gray-200 dark:border-gray-600 min-w-[3rem] text-center"
                      >
                        {{ item.quantity }}
                      </span>
                      <button
                        @click="updateQuantity(item.id, 1)"
                        class="cursor-pointer px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
                        :disabled="item.quantity >= item.book.stock"
                        title="Tambah jumlah"
                      >
                        +
                      </button>
                    </div>

                    <button
                      @click="removeItem(item.id)"
                      class="cursor-pointer flex gap-1 items-center px-2 lg:px-3 py-1 lg:py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors"
                      title="Hapus item"
                    >
                      <ThrashIcon class="w-4 h-4" />
                      Hapus
                    </button>
                  </div>
                </div>

                <div
                  class="text-sm lg:text-lg font-medium text-gray-900 text-right dark:text-gray-200"
                >
                  {{ formatPrice(item.book.price * item.quantity) }}
                </div>
              </div>
            </div>
          </div>

          <!-- Continue Shopping -->
          <div class="mt-6 pt-4 border-t border-gray-200 lg:border-0 dark:border-gray-700">
            <RouterLink
              to="/shop"
              class="cursor-pointer inline-flex items-center px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors rounded"
            >
              ← Lanjutkan Belanja
            </RouterLink>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="order-1 lg:order-2 lg:flex-1 lg:col-span-4 lg:sticky lg:top-8">
          <div
            class="bg-white border border-gray-200 rounded-lg overflow-hidden dark:border-gray-700 dark:bg-gray-800"
          >
            <div class="px-5 lg:px-6 py-4 border-b border-gray-100 dark:border-gray-700">
              <h2 class="text-lg font-semibold dark:text-gray-200">Ringkasan Pesanan</h2>
            </div>

            <div class="px-5 lg:px-6 py-4 flex flex-col gap-3 lg:gap-4 dark:text-gray-200">
              <div class="flex items-center justify-between dark:text-gray-300">
                <span>Subtotal ({{ cartItems.length }} item)</span>
                <span>{{ formatPrice(subtotal) }}</span>
              </div>
              <div class="flex items-center justify-between dark:text-gray-300">
                <span class="flex items-center gap-1">
                  Pengiriman
                  <span v-if="shipping === 0" class="text-xs text-green-600 dark:text-green-400"
                    >(Gratis)</span
                  >
                </span>
                <span>{{ formatPrice(shipping) }}</span>
              </div>
              <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                <div
                  class="flex items-center justify-between font-semibold text-lg dark:text-gray-200"
                >
                  <span>Total</span>
                  <span>{{ formatPrice(total) }}</span>
                </div>
              </div>
            </div>

            <div class="px-5 lg:px-6 py-4 border-t border-gray-100 dark:border-gray-700">
              <button
                class="cursor-pointer w-full py-3 bg-gray-800 text-white text-sm lg:text-base font-semibold rounded hover:bg-gray-700 transition-colors dark:bg-gray-600 dark:hover:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="cartItems.length === 0"
              >
                Lanjutkan ke Pembayaran
              </button>
              <p class="text-xs text-gray-500 mt-2 text-center dark:text-gray-400">
                Gratis ongkir untuk pembelian di atas {{ formatPrice(100000) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty Cart -->
    <div
      v-else-if="!loading && cartItems.length === 0"
      class="container mx-auto px-4 py-12 text-center"
    >
      <div class="max-w-md mx-auto">
        <div
          class="w-24 h-24 mx-auto mb-6 bg-gray-200 rounded-full flex items-center justify-center dark:bg-gray-700"
        >
          <svg
            class="w-12 h-12 text-gray-400 dark:text-gray-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0V9"
            ></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold mb-4 dark:text-gray-200">Keranjang Anda Kosong</h2>
        <p class="text-gray-600 mb-6 dark:text-gray-400">
          Tambahkan beberapa buku untuk memulai belanja!
        </p>
        <RouterLink
          to="/shop"
          class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium"
        >
          Mulai Belanja
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom styles for better mobile experience */
@media (max-width: 768px) {
  .lg\:flex-2 {
    flex: 1;
  }
}

/* Loading animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>

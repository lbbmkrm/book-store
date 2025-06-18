<script setup>
import ThrashIcon from '@/components/icons/thrash-icon.vue'
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useToast } from 'vue-toastification'

const router = useRouter()
const cartStore = useCartStore()
const toast = useToast()
const fetchCart = async () => {
  try {
    await cartStore.fetchCart()
    console.log('Cart items fetched successfully')
  } catch (error) {
    if (error.response?.status === 401) {
      router.push('/login')
    } else {
      console.log(cartStore.errorMessage || 'Terjadi kesalahan')
    }
  }
}

const updateQuantity = async (itemId, quantity) => {
  try {
    await cartStore.updateQuantity(itemId, quantity)
    console.log('success update item quantity')
  } catch (error) {
    toast.error('Gagal memperbarui item')
    console.error(cartStore.errorMessage)
  }
}

const removeItem = async (itemId) => {
  try {
    await cartStore.removeItem(itemId)
  } catch (error) {
    toast.error('Gagal menghapus item')
    console.error('Error removing item:', error.response.data)
  }
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(price)
}

const subtotal = computed(() => {
  let total = 0
  cartStore.cartItems.forEach((item) => {
    total += item.book.price * item.quantity
  })
  localStorage.setItem('subtotal', total)
  return total
})

const shipping = computed(() => {
  let cost = 0
  if (subtotal.value <= 100000) {
    cost = 40000
  } else if (subtotal.value <= 250000) {
    cost = 20000
  } else {
    cost = 0
  }
  localStorage.setItem('shippingCost', cost)
  return cost
})
const total = computed(() => {
  localStorage.setItem('total', subtotal.value + shipping.value)
  return subtotal.value + shipping.value
})

onMounted(() => {
  fetchCart()
})
</script>

<template class="dark:bg-transparent">
  <div class="min-h-screen mt-18 bg-gray-50 py-8 font-sans dark:bg-gray-800 dark:text-gray-200">
    <div
      v-if="cartStore.cartItems.length > 0"
      class="container mx-auto px-4 dark:bg-gray-800 dark:border-gray-700"
    >
      <!-- Header -->
      <div
        class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-700"
      >
        <h1 class="text-2xl font-bold font-header">Keranjang Belanja</h1>
        <span class="text-gray-600 text-sm lg:text-base dark:text-gray-400 font-label">
          {{ cartStore.cartItems.length }} item
        </span>
      </div>

      <!-- Main Layout -->
      <div class="flex flex-col lg:flex-row gap-8 font-label dark:bg-gray-800">
        <!-- Cart Items -->
        <div class="order-1 lg:flex-2 dark:bg-transparent lg:col-span-8">
          <div
            class="bg-white rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700 dark:bg-gray-800"
          >
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
              <!-- Item -->
              <div
                v-for="item in cartStore.cartItems"
                :key="item.id"
                class="flex items-start gap-4 lg:gap-6 py-4 dark:border-b dark:border-gray-700"
              >
                <div class="w-16 h-20 lg:w-24 lg:h-32 flex-shrink-0">
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

                <div class="flex-1 min-w-0 dark:text-gray-200">
                  <div class="mb-2 lg:mb-3 dark:text-gray-300">
                    <h3
                      class="text-sm lg:text-lg font-medium font-body text-gray-900 mb-1 dark:text-gray-200"
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
                        class="cursor-pointer px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-gray-700"
                        :disabled="item.quantity <= 1"
                      >
                        −
                      </button>
                      <span
                        class="px-3 lg:px-4 py-1 lg:py-2 text-sm border-x border-gray-200 dark:border-gray-600"
                      >
                        {{ item.quantity }}
                      </span>
                      <button
                        @click="updateQuantity(item.id, 1)"
                        class="cursor-pointer px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:text-gray-300 dark:hover:bg-gray-700"
                        :disabled="item.quantity >= item.stock"
                      >
                        +
                      </button>
                    </div>

                    <button
                      @click="removeItem(item.id)"
                      class="cursor-pointer flex gap-1 items-center px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700"
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
            <button
              class="cursor-pointer px-2 lg:px-3 py-1 lg:py-2 text-sm text-gray-600 font-label hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              ← Lanjutkan Belanja
            </button>
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
                <span>Subtotal</span>
                <span>{{ formatPrice(subtotal) }}</span>
              </div>
              <div class="flex items-center justify-between dark:text-gray-300">
                <span>Pengiriman</span>
                <span>{{ formatPrice(shipping) }}</span>
              </div>
              <div class="flex items-center justify-between dark:text-gray-300">
                <span>Total</span>
                <span>{{ formatPrice(total) }}</span>
              </div>
            </div>

            <div
              class="w-full px-5 lg:px-6 py-4 border-t flex justify-center border-gray-100 dark:border-gray-700"
            >
              <RouterLink
                v-on:click="setCheckoutData"
                to="/checkout"
                class="cursor-pointer w-full px-3 text-center py-2 bg-gray-800 text-white text-sm lg:text-base font-semibold rounded hover:bg-gray-700 transition-colors dark:bg-gray-600 dark:hover:bg-gray-500"
              >
                Lanjutkan ke Pembayaran
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="container mt-18 mx-auto px-4 py-12 text-center">
      <h2 class="text-2xl font-bold mb-4">Keranjang Anda Kosong</h2>
      <p class="text-gray-600 mb-6">Tambahkan beberapa buku untuk memulai belanja!</p>
      <RouterLink
        to="/shop"
        class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
      >
        Mulai Belanja
      </RouterLink>
    </div>
  </div>
</template>

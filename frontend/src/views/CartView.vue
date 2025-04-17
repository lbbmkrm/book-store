<script setup>
import { ref, computed } from 'vue'

const cartItems = ref([
  {
    id: 1,
    name: 'Buku Pemrograman JavaScript',
    price: 125000,
    image: 'javascript_book.jpg',
    quantity: 2,
    color: 'Biru',
    size: 'A4',
  },
  {
    id: 2,
    name: 'Buku Python untuk Pemula',
    price: 95000,
    image: 'python_book.jpg',
    quantity: 1,
    color: 'Hijau',
    size: 'A5',
  },
])

const isLoading = ref(false)

const subtotal = computed(() =>
  cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0),
)
const tax = computed(() => subtotal.value * 0.12)
const shipping = ref(15000)
const total = computed(() => subtotal.value + tax.value + shipping.value)

const updateQuantity = (item, newQuantity) => {
  if (newQuantity > 0) {
    item.quantity = newQuantity
  }
}

const removeItem = (itemId) => {
  cartItems.value = cartItems.value.filter((item) => item.id !== itemId)
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-semibold mb-8">Keranjang Belanja</h1>

      <div v-if="isLoading" class="text-center py-8">
        <p>Memuat keranjang...</p>
      </div>

      <div v-else class="flex flex-col lg:flex-row gap-8">
        <!-- Cart Items -->
        <div class="lg:w-2/3">
          <div class="bg-white rounded-lg shadow">
            <!-- Empty Cart State -->
            <div v-if="cartItems.length === 0" class="p-6 text-center">
              <p class="text-gray-500">Keranjang Anda kosong</p>
              <router-link to="/products" class="text-blue-600 hover:underline mt-2 inline-block">
                Lanjutkan Belanja
              </router-link>
            </div>

            <!-- Cart Items List -->
            <div v-else>
              <div
                v-for="item in cartItems"
                :key="item.id"
                class="flex items-start space-x-4 p-6 border-b border-gray-200"
              >
                <div class="w-24 h-32 bg-gray-200 flex-shrink-0">
                  <img
                    :src="`/api/placeholder/120/160`"
                    :alt="item.name"
                    class="w-full h-full object-cover"
                  />
                </div>

                <div class="flex-1">
                  <div class="flex justify-between">
                    <div>
                      <h3 class="text-lg font-medium">{{ item.name }}</h3>
                      <p class="text-gray-600">Penulis</p>
                      <p class="text-gray-500 text-sm">Penerbit</p>
                    </div>
                    <p class="font-medium">
                      Rp {{ (item.price * item.quantity).toLocaleString() }}
                    </p>
                  </div>

                  <div class="flex items-center space-x-4 mt-4">
                    <div class="flex items-center border rounded-md">
                      <button
                        @click="updateQuantity(item, item.quantity - 1)"
                        class="px-3 py-1 hover:bg-gray-100"
                      >
                        -
                      </button>
                      <span class="px-3 py-1 border-x">{{ item.quantity }}</span>
                      <button
                        @click="updateQuantity(item, item.quantity + 1)"
                        class="px-3 py-1 hover:bg-gray-100"
                      >
                        +
                      </button>
                    </div>
                    <button @click="removeItem(item.id)" class="text-red-500 hover:text-red-700">
                      Hapus
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
          <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>

            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span>Rp {{ subtotal.toLocaleString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Pajak (12%)</span>
                <span>Rp {{ Math.round(tax).toLocaleString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Pengiriman</span>
                <span>Rp {{ shipping.toLocaleString() }}</span>
              </div>
              <div class="border-t pt-3">
                <div class="flex justify-between font-semibold">
                  <span>Total</span>
                  <span>Rp {{ Math.round(total).toLocaleString() }}</span>
                </div>
              </div>
            </div>

            <router-link
              to="/checkout"
              class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors mt-6 inline-block text-center"
            >
              Lanjutkan ke Pembayaran
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

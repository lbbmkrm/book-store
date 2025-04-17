<script setup>
import { ref, computed } from 'vue'

// Data statis untuk keranjang belanja toko buku
const cartItems = ref([
  {
    id: 1,
    name: 'Laskar Pelangi',
    author: 'Andrea Hirata',
    price: 85000,
    image: 'https://via.placeholder.com/150/9B5DE5/FFFFFF?text=Laskar+Pelangi',
    quantity: 1,
    format: 'Paperback',
    publisher: 'Bentang Pustaka',
  },
  {
    id: 2,
    name: 'Bumi Manusia',
    author: 'Pramoedya Ananta Toer',
    price: 95000,
    image: 'https://via.placeholder.com/150/00A896/FFFFFF?text=Bumi+Manusia',
    quantity: 2,
    format: 'Hardcover',
    publisher: 'Lentera Dipantara',
  },
  {
    id: 3,
    name: 'Filosofi Teras',
    author: 'Henry Manampiring',
    price: 78000,
    image: 'https://via.placeholder.com/150/FF8811/FFFFFF?text=Filosofi+Teras',
    quantity: 1,
    format: 'Paperback',
    publisher: 'Kompas',
  },
])

const isLoading = ref(false)

// Komputasi untuk subtotal, pajak, dan total
const subtotal = computed(() =>
  cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0),
)
const tax = computed(() => subtotal.value * 0.1)
const total = computed(() => subtotal.value + tax.value)

// Fungsi untuk memperbarui jumlah item
const updateQuantity = (item, newQuantity) => {
  if (newQuantity > 0) {
    item.quantity = newQuantity
  }
}

// Fungsi untuk menghapus item dari keranjang
const removeItem = (itemId) => {
  cartItems.value = cartItems.value.filter((item) => item.id !== itemId)
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-semibold mb-8">Keranjang Belanja</h1>

      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cart Items -->
        <div class="lg:w-2/3">
          <div class="bg-white rounded-lg shadow">
            <!-- Empty Cart State -->
            <div v-if="cartItems.length === 0" class="p-6 text-center">
              <p class="text-gray-500">Keranjang belanja Anda kosong</p>
              <router-link to="/books" class="text-blue-600 hover:underline mt-2 inline-block">
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
                <img :src="item.image" :alt="item.name" class="w-24 h-32 object-cover rounded-md" />

                <div class="flex-1">
                  <div class="flex justify-between">
                    <div>
                      <h3 class="text-lg font-medium">{{ item.name }}</h3>
                      <p class="text-gray-600 text-sm">Penulis: {{ item.author }}</p>
                      <p class="text-gray-500 text-sm">Penerbit: {{ item.publisher }}</p>
                      <p class="text-gray-500 text-sm">Format: {{ item.format }}</p>
                    </div>
                    <p class="font-medium">
                      Rp {{ (item.price * item.quantity).toLocaleString('id-ID') }}
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
                <span>Rp {{ subtotal.toLocaleString('id-ID') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Pajak (10%)</span>
                <span>Rp {{ tax.toLocaleString('id-ID') }}</span>
              </div>
              <div class="border-t pt-3">
                <div class="flex justify-between font-semibold">
                  <span>Total</span>
                  <span>Rp {{ total.toLocaleString('id-ID') }}</span>
                </div>
              </div>
            </div>

            <router-link
              to="/checkout"
              class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors mt-6 inline-block text-center"
            >
              Lanjutkan ke Pembayaran
            </router-link>

            <div class="mt-4 text-center">
              <router-link to="/books" class="text-blue-600 hover:underline">
                Lanjutkan Belanja
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

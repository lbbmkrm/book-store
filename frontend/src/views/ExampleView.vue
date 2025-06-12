<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">My Orders</h1>
        <p class="text-gray-600">Track and manage your book orders</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-16">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        <span class="ml-3 text-gray-600">Loading orders...</span>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error loading orders</h3>
            <p class="text-sm text-red-700 mt-1">{{ error }}</p>
          </div>
        </div>
        <button
          @click="fetchOrders"
          class="mt-4 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors"
        >
          Try Again
        </button>
      </div>

      <!-- Empty State -->
      <div v-else-if="orders.length === 0" class="text-center py-16">
        <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
            />
          </svg>
        </div>
        <h3 class="text-xl font-medium text-gray-900 mb-2">No orders yet</h3>
        <p class="text-gray-500 mb-6">Start shopping to see your orders here</p>
        <button
          class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors"
        >
          Browse Books
        </button>
      </div>

      <!-- Orders List -->
      <div v-else class="space-y-6">
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
        >
          <!-- Order Header -->
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between">
              <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                <div
                  class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium"
                >
                  Order #{{ order.id }}
                </div>
                <div class="text-sm text-gray-600">
                  {{ formatDate(order.created_at) }}
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-lg font-semibold text-gray-900">
                  ${{ order.total_price.toFixed(2) }}
                </div>
                <button
                  @click="toggleOrderDetails(order.id)"
                  class="text-indigo-600 hover:text-indigo-800 font-medium text-sm"
                >
                  {{ expandedOrders.has(order.id) ? 'Hide Details' : 'View Details' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Order Content -->
          <div class="px-6 py-4">
            <!-- Shipping Address -->
            <div class="mb-4">
              <div class="flex items-center text-sm text-gray-600 mb-1">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
                Shipping Address:
              </div>
              <p class="text-gray-900 font-medium">{{ order.shipping_address }}</p>
            </div>

            <!-- Order Items Preview -->
            <div v-if="!expandedOrders.has(order.id)" class="mb-4">
              <div class="text-sm text-gray-600 mb-2">
                {{ order.order_details?.length || 0 }} item(s)
              </div>
              <div class="flex flex-wrap gap-2">
                <div
                  v-for="item in (order.order_details || []).slice(0, 3)"
                  :key="item.id"
                  class="bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-xs"
                >
                  {{ item.book?.title || 'Unknown Book' }} ({{ item.quantity }})
                </div>
                <div
                  v-if="(order.order_details?.length || 0) > 3"
                  class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs"
                >
                  +{{ (order.order_details?.length || 0) - 3 }} more
                </div>
              </div>
            </div>

            <!-- Expanded Order Details -->
            <div v-if="expandedOrders.has(order.id)" class="space-y-4">
              <div class="border-t pt-4">
                <h4 class="font-medium text-gray-900 mb-3">Order Items</h4>
                <div class="space-y-3">
                  <div
                    v-for="item in order.order_details || []"
                    :key="item.id"
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                  >
                    <div class="flex items-center space-x-4">
                      <div
                        class="flex-shrink-0 w-16 h-20 bg-gray-200 rounded-md flex items-center justify-center"
                      >
                        <img
                          v-if="item.book?.img"
                          :src="`/images/${item.book.img}`"
                          :alt="item.book?.title"
                          class="w-full h-full object-cover rounded-md"
                          @error="handleImageError"
                        />
                        <svg
                          v-else
                          class="h-8 w-8 text-gray-400"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                          />
                        </svg>
                      </div>
                      <div>
                        <h5 class="font-medium text-gray-900">
                          {{ item.book?.title || 'Unknown Book' }}
                        </h5>
                        <p class="text-sm text-gray-600">
                          by {{ item.book?.author || 'Unknown Author' }}
                        </p>
                        <p class="text-sm text-gray-500">Quantity: {{ item.quantity }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="font-medium text-gray-900">
                        ${{ (item.price * item.quantity).toFixed(2) }}
                      </div>
                      <div class="text-sm text-gray-600">${{ item.price.toFixed(2) }} each</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Order Summary -->
              <div class="border-t pt-4">
                <div class="bg-indigo-50 rounded-lg p-4">
                  <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-900">Total Amount:</span>
                    <span class="text-xl font-bold text-indigo-600"
                      >${{ order.total_price.toFixed(2) }}</span
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination (if needed) -->
      <div v-if="orders.length > 0" class="mt-8 flex justify-center">
        <button
          class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-l-md hover:bg-gray-50"
        >
          Previous
        </button>
        <button class="bg-indigo-600 text-white px-4 py-2 border-t border-b border-indigo-600">
          1
        </button>
        <button
          class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-r-md hover:bg-gray-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Reactive state
const orders = ref([])
const loading = ref(true)
const error = ref(null)
const expandedOrders = ref(new Set())

// Static data to replace API response
const staticOrders = [
  {
    id: 101,
    created_at: '2024-05-01T14:23:00Z',
    total_price: 59.97,
    shipping_address: '123 Main St, Springfield',
    order_details: [
      {
        id: 1,
        book: {
          title: 'Vue.js Basics',
          author: 'Evan You',
          img: 'vuejs-basics.jpg',
        },
        quantity: 1,
        price: 19.99,
      },
      {
        id: 2,
        book: {
          title: 'Advanced Vue Patterns',
          author: 'Sarah Drasner',
          img: 'advanced-vue.jpg',
        },
        quantity: 2,
        price: 19.99,
      },
    ],
  },
  {
    id: 102,
    created_at: '2024-05-03T10:15:00Z',
    total_price: 39.99,
    shipping_address: '456 Elm St, Shelbyville',
    order_details: [
      {
        id: 3,
        book: {
          title: 'JavaScript Essentials',
          author: 'Kyle Simpson',
          img: 'js-essentials.jpg',
        },
        quantity: 1,
        price: 39.99,
      },
    ],
  },
]

// Simulate fetchOrders by loading static data
const fetchOrders = () => {
  loading.value = true
  error.value = null

  try {
    // Simulate async delay (optional)
    setTimeout(() => {
      orders.value = staticOrders
      loading.value = false
    }, 500)
  } catch (err) {
    error.value = 'Failed to load orders'
    loading.value = false
  }
}

// Toggle order details visibility
const toggleOrderDetails = (orderId) => {
  if (expandedOrders.value.has(orderId)) {
    expandedOrders.value.delete(orderId)
  } else {
    expandedOrders.value.add(orderId)
  }
}

// Format date helper
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'

  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  } catch {
    return dateString
  }
}

// Image error handler (optional)
const handleImageError = (event) => {
  event.target.src = '/images/default-book.png' // fallback image path
}

onMounted(() => {
  fetchOrders()
})
</script>

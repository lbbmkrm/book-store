import { computed, ref } from 'vue'
import axios from 'axios'
import { defineStore } from 'pinia'
import { useRouter } from 'vue-router'
const apiUrl = import.meta.env.VITE_API_SERVER
export const useCart = defineStore('cart', () => {
  const router = useRouter()
  const cart = ref({})
  const cartItems = ref([])
  const errorResponse = ref(null)
  const errorMessage = ref(null)
  const subTotalValue = computed(() => {
    let total = 0
    cartItems.value.forEach((item) => {
      total += item.book.price * item.quantity
    })
    return total
  })

  const shippingCostValue = computed(() => {
    if (subTotalValue.value <= 100000) return 40000
    if (subTotalValue.value <= 250000) return 20000
    return 0
  })

  const totalValue = computed(() => {
    return subTotalValue.value + shippingCostValue.value
  })

  const subTotal = computed(() => formatPrice(subTotalValue.value))
  const shippingCost = computed(() => formatPrice(shippingCostValue.value))
  const total = computed(() => formatPrice(totalValue.value))

  async function fetchCart() {
    try {
      const response = await axios.get(`${apiUrl}/cart`, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        },
      })
      cart.value = response.data.data
      cartItems.value = response.data.data.cartDetails
    } catch (error) {
      errorResponse.value = error.response
      errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan'
      throw error
    }
  }

  async function updateQuantity(itemId, quantity) {
    const item = cartItems.value.find((item) => {
      return item.id === itemId
    })
    if (!item) {
      return
    }
    const newQuantity = item.quantity + quantity
    if (newQuantity < 1 || newQuantity > item.stock) {
      return
    }
    try {
      const response = await axios.put(
        `${apiUrl}/cart/${itemId}`,
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
      item.quantity = newQuantity
    } catch (error) {
      errorResponse.value = error.response
      errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan'
      throw error
    }
  }

  async function removeItem(itemId) {
    try {
      const response = await axios.delete(`${apiUrl}/cart/${itemId}`, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        },
      })
      cartItems.value = cartItems.value.filter((item) => item.id !== itemId)
    } catch (error) {
      errorResponse.value = error.response
      errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan'
      throw error
    }
  }

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
    }).format(price)
  }
  return {
    cart,
    cartItems,
    errorResponse,
    errorMessage,
    total,
    subTotal,
    shippingCost,
    fetchCart,
    updateQuantity,
    removeItem,
    formatPrice,
  }
})

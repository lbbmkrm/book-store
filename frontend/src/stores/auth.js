import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('authToken') || null)
  const user = ref(JSON.parse(localStorage.getItem('user')) || null)
  const isAuthenticated = ref(token.value ? true : false)

  axios.defaults.baseURL = import.meta.env.VITE_API_SERVER

  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  async function login(credentials) {
    try {
      const response = await axios.post('/login', credentials)

      token.value = response.data.token
      user.value = response.data.data
      isAuthenticated.value = true

      localStorage.setItem('authToken', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))

      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

      return response.data
    } catch (error) {
      throw error.response.data
    }
  }

  async function register(credentials) {
    try {
      const response = await axios.post('/register', credentials, {
        headers: {
          'Content-Type': 'application/json',
        },
      })
      token.value = response.data.token
      user.value = response.data.data
      return response
    } catch (error) {
      console.error(error.response.data.message)
      return error.response
    }
  }

  async function fetchUser() {
    try {
      const response = await axios.get('/authenticated', {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token.value}`,
        },
      })
      user.value = response.data.data
      return response
    } catch (error) {
      console.error(error.response.data.message)
      return error.response
    }
  }

  function logout() {
    try {
      const response = axios.post('/logout')
      token.value = null
      user.value = null
      isAuthenticated.value = false
      localStorage.removeItem('authToken')
      localStorage.removeItem('user')
      delete axios.defaults.headers.common['Authorization']
    } catch (error) {
      throw error.response
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    fetchUser,
    logout,
  }
})

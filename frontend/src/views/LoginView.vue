<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const isLoading = ref(false)
const emailError = ref('')
const passwordError = ref('')

const validateForm = () => {
  let isValid = true

  // Validasi email
  if (!email.value) {
    emailError.value = 'Email tidak boleh kosong'
    isValid = false
  } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
    emailError.value = 'Format email tidak valid'
    isValid = false
  } else {
    emailError.value = ''
  }

  // Validasi password
  if (!password.value) {
    passwordError.value = 'Password tidak boleh kosong'
    isValid = false
  } else if (password.value.length < 6) {
    passwordError.value = 'Password minimal 6 karakter'
    isValid = false
  } else {
    passwordError.value = ''
  }

  return isValid
}

const handleLogin = async () => {
  if (!validateForm()) {
    return
  }

  try {
    isLoading.value = true

    // Simulasi API call
    await new Promise((resolve) => setTimeout(resolve, 1500))

    // Di sini Anda bisa memanggil API atau fungsi autentikasi sebenarnya
    console.log('Login dengan:', {
      email: email.value,
      password: password.value,
      rememberMe: rememberMe.value,
    })

    // Reset form setelah berhasil
    email.value = ''
    password.value = ''
    rememberMe.value = false

    alert('Login berhasil!')
  } catch (error) {
    console.error('Login gagal:', error)
    alert('Login gagal. Silakan coba lagi.')
  } finally {
    isLoading.value = false
  }
}
</script>
<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Login</h1>
        <p class="text-gray-600 mt-2">Silakan masuk ke akun Anda</p>
      </div>

      <form @submit.prevent="handleLogin">
        <div class="mb-6">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Masukkan email Anda"
            required
          />
          <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
        </div>

        <div class="mb-6">
          <div class="flex justify-between items-center mb-2">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <a href="#" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
          </div>
          <input
            type="password"
            id="password"
            v-model="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Masukkan password Anda"
            required
          />
          <p v-if="passwordError" class="mt-1 text-sm text-red-600">{{ passwordError }}</p>
        </div>

        <div class="flex items-center mb-6">
          <input
            type="checkbox"
            id="remember"
            v-model="rememberMe"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
          />
          <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          :disabled="isLoading"
        >
          <span v-if="isLoading">Loading...</span>
          <span v-else>Login</span>
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
          Belum memiliki akun?
          <router-link to="/register" class="text-blue-600 hover:underline font-medium">
            Daftar di sini
          </router-link>
        </p>
      </div>

      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Atau login dengan</span>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
          <button
            type="button"
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Google
          </button>
          <button
            type="button"
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Facebook
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

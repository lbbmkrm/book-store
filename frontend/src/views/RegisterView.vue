<!-- src/components/Register.vue -->
<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

const toast = useToast()
const authStore = useAuthStore()
const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const address = ref('')
const phone = ref('')
const errorMessage = ref('')
const isLoading = ref(false)

const validateForm = () => {
  if (password.value !== confirmPassword.value) {
    errorMessage.value = 'Password dan konfirmasi password tidak cocok'
    return false
  }

  if (password.value.length < 8) {
    errorMessage.value = 'Password minimal 8 karakter'
    return false
  }

  const phoneRegex = /^[0-9+\-\s()]+$/
  if (!phoneRegex.test(phone.value)) {
    errorMessage.value = 'Format nomor telepon tidak valid'
    return false
  }

  return true
}

const register = async () => {
  errorMessage.value = ''

  if (!validateForm()) {
    toast.error(errorMessage.value)
    return
  }

  isLoading.value = true

  try {
    await authStore.register({
      name: name.value,
      email: email.value,
      password: password.value,
      address: address.value,
      phone: phone.value,
    })

    toast.success('Registrasi berhasil! Silakan login.')
    router.push('/login')
  } catch (error) {
    if (error.status === 422) {
      errorMessage.value = error.message || 'Periksa kembali input Anda'
    } else if (error.status === 409) {
      errorMessage.value = 'Email sudah terdaftar'
    } else {
      errorMessage.value = error.message || 'Registrasi gagal'
    }
    toast.error(errorMessage.value)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div
    class="flex items-center justify-center min-h-screen px-4 py-8 bg-gradient-to-r from-gray-50 to-gray-100 dark:bg-gray-900 dark:from-gray-800 dark:to-gray-700"
  >
    <div
      id="register-form"
      class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 hover:shadow-xl transition-all ease-in-out duration-200 delay-75 dark:bg-gray-800 dark:text-white"
    >
      <div class="mb-6 text-center dark:text-gray-200">
        <h1 class="text-[32px] text-gray-800 dark:text-gray-200">Create your account</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Join us today and get started</p>
      </div>

      <div class="mb-6">
        <form
          @submit.prevent="register"
          class="space-y-4 hover:shadow-lg transition-shadow duration-300 dark:hover:shadow-xl"
        >
          <!-- First Row: Name and Email -->
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex flex-col gap-2 flex-1">
              <label for="name" class="dark:text-gray-200">Full Name</label>
              <input
                v-model="name"
                type="text"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Enter your full name"
                id="name"
                autocomplete="name"
              />
            </div>

            <div class="flex flex-col gap-2 flex-1">
              <label for="email" class="dark:text-gray-200">Email</label>
              <input
                v-model="email"
                type="email"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Enter your email"
                id="email"
                autocomplete="email"
              />
            </div>
          </div>

          <!-- Second Row: Password and Confirm Password -->
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex flex-col gap-2 flex-1">
              <label for="password" class="dark:text-gray-200">Password</label>
              <input
                v-model="password"
                type="password"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Enter your password"
                id="password"
                autocomplete="new-password"
                minlength="8"
              />
              <p class="text-xs text-gray-500 dark:text-gray-400">Minimal 8 karakter</p>
            </div>

            <div class="flex flex-col gap-2 flex-1">
              <label for="confirmPassword" class="dark:text-gray-200">Confirm Password</label>
              <input
                v-model="confirmPassword"
                type="password"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Confirm your password"
                id="confirmPassword"
                autocomplete="new-password"
              />
            </div>
          </div>

          <!-- Third Row: Phone and Address -->
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex flex-col gap-2 flex-1">
              <label for="phone" class="dark:text-gray-200">Phone Number</label>
              <input
                v-model="phone"
                type="tel"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Enter your phone number"
                id="phone"
                autocomplete="tel"
              />
            </div>

            <div class="flex flex-col gap-2 flex-1">
              <label for="address" class="dark:text-gray-200">Address</label>
              <textarea
                v-model="address"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200 resize-none"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Enter your address"
                id="address"
                autocomplete="street-address"
                rows="3"
              ></textarea>
            </div>
          </div>

          <!-- Terms and Conditions -->
          <div class="flex items-start gap-2 mb-4">
            <input
              type="checkbox"
              id="terms"
              required
              class="mt-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <label for="terms" class="text-sm dark:text-gray-200">
              I agree to the
              <a
                href="#"
                class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200 dark:text-indigo-400 dark:hover:text-indigo-600"
              >
                Terms and Conditions
              </a>
              and
              <a
                href="#"
                class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200 dark:text-indigo-400 dark:hover:text-indigo-600"
              >
                Privacy Policy
              </a>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="cursor-pointer w-full bg-indigo-600 text-white font-semibold rounded-lg px-4 py-2 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200 dark:bg-indigo-500 dark:hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isLoading" class="flex items-center justify-center">
              <svg
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              Creating account...
            </span>
            <span v-else>Create Account</span>
          </button>
        </form>
      </div>

      <div>
        <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
          Already have an account?
          <router-link
            to="/login"
            class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200 dark:text-indigo-400 dark:hover:text-indigo-600"
          >
            Sign in
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

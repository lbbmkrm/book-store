<!-- src/components/Login.vue -->
<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useToast } from 'vue-toastification'
import { useRouter, useRoute, RouterLink } from 'vue-router'

const toast = useToast()
const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()
const email = ref('')
const password = ref('')
const errorMessage = ref('')

const login = async () => {
  try {
    await authStore.login({
      email: email.value,
      password: password.value,
    })
    const redirectPath = route.query.redirect ? route.query.redirect : '/'
    console.log('Redirecting to:', redirectPath)
    router.push(redirectPath)
  } catch (error) {
    if (error.status === 422) {
      errorMessage.value = error.message || 'Periksa kembali input Anda'
    } else if (error.status === 401) {
      errorMessage.value = 'Email atau kata sandi salah'
    } else {
      errorMessage.value = error.message || 'Login gagal'
    }
    toast.error(errorMessage.value)
  }
}
</script>

<template>
  <div
    class="flex items-center justify-center min-h-screen px-4 py-8 bg-gradient-to-r from-gray-50 to-gray-100 dark:bg-gray-900 dark:from-gray-800 dark:to-gray-700"
  >
    <div
      id="login-form"
      class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 hover:shadow-xl transition-all ease-in-out duration-200 delay-75 dark:bg-gray-800 dark:text-white"
    >
      <div class="mb-6 text-center dark:text-gray-200">
        <h1 class="text-[32px] text-gray-800 dark:text-gray-200">Sign in to your account</h1>
      </div>
      <div class="mb-6">
        <form
          @submit.prevent="login"
          class="space-y-6 hover:shadow-lg transition-shadow duration-300 dark:hover:shadow-xl"
        >
          <div class="space-y-4 dark:text-gray-200">
            <div class="flex flex-col gap-2 mb-4 dark:text-gray-200">
              <label for="email">Email</label>
              <input
                v-model="email"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                required
                placeholder="Enter your email"
                id="email"
                autocomplete="off"
              />
            </div>
            <div class="flex flex-col gap-2 mb-4">
              <label for="password">Password</label>
              <input
                v-model="password"
                type="password"
                class="border rounded-lg p-2 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"
                :class="
                  errorMessage
                    ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                    : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500'
                "
                placeholder="Enter your password"
                id="password"
              />
            </div>
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-2 dark:text-gray-200">
                <input type="checkbox" />
                <p>Remember me</p>
              </div>
              <p
                class="cursor-pointer text-indigo-600 hover:text-indigo-800 transition-colors duration-200 dark:text-indigo-400 dark:hover:text-indigo-600"
              >
                Forgot password?
              </p>
            </div>
            <button
              class="cursor-pointer w-full bg-indigo-600 text-white font-semibold rounded-lg px-4 py-2 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200 dark:bg-indigo-500 dark:hover:bg-indigo-600"
            >
              Sign in
            </button>
          </div>
        </form>
      </div>
      <div>
        <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
          Don't have an account?
          <RouterLink
            to="/register"
            href="#"
            class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200 dark:text-indigo-400 dark:hover:text-indigo-600"
            >Sign up</RouterLink
          >
        </p>
      </div>
    </div>
  </div>
</template>

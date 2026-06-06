<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const email = ref('')
const password = ref('')
const isLoading = ref(false)

async function login() {
  isLoading.value = true
  try {
    await authStore.login(email.value, password.value)
    toast.success('Login success')
    router.push('/')
  } catch (error) {
    console.error('Login error:', error)
    toast.error('Wrong Credential')
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="flex items-center justify-center h-screen w-full">
    <div class="flex flex-col items-center max-w-md rounded-2xl shadow-xl px-40 py-8">
      <h1 class="text-3xl font-bold">LOGIN</h1>

      <div id="input-form" class="flex flex-col pt-6 px-12">
        <div class="">
          <p class="text-md font-semibold">Email</p>
          <input
            v-model="email"
            class="border-3 border-gray-300 active:border-gray-300 rounded-lg py-2 px-4"
            type="email"
            placeholder="youremail@mail.com"
          />
        </div>

        <div class="mt-6">
          <p class="text-md font-semibold">Password</p>
          <input
            v-model="password"
            class="border-3 border-gray-300 active:border-gray-300 rounded-lg py-2 px-4"
            type="password"
            placeholder="password"
          />
        </div>

        <button
          type="button"
          :disabled="isLoading"
          class="bg-sky-500 mt-6 px-4 py-1 rounded-md"
          @click="login"
        >
          <p class="text-white font-semibold">{{ isLoading ? 'Loading...' : 'Login' }}</p>
        </button>
      </div>
    </div>
  </div>
</template>

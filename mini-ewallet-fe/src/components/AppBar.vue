<script setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const isOpen = ref(false)
const dropdownRef = ref(null)
const router = useRouter()
const authStore = useAuthStore()

onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

function toggleDropdown() {
  isOpen.value = !isOpen.value
}

function closeDropdown() {
  isOpen.value = false
}

function logout() {
  closeDropdown()
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <header class="flex h-16 items-center justify-end border-b border-gray-100 bg-white px-6 shadow-sm">
    <div class="relative" ref="dropdownRef">
      <!-- icon -->
      <a href="#" @click="toggleDropdown">
        <i class="fa-solid fa-circle-user text-2xl"></i>
      </a>

      <!-- dropdown -->
      <div v-if="isOpen"
        class="absolute right-0 mt-2 w-44 rounded-lg border border-gray-100 bg-white shadow-lg py-1 z-50">

        <hr class="my-1 border-gray-100" />

        <button class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50" @click="logout">
          <i class="fa-solid fa-right-from-bracket w-4"></i>
          <p>Logout</p>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
import SendMoneyModal from '@/components/SendMoneyModal.vue'
import { useAuthStore } from '@/stores/auth'
import { onMounted, ref } from 'vue'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()

const isBalanceVisible = ref(true)
const isModalOpen = ref(false)
const isLoading = ref(true)

onMounted(async () => {
  try {
    await authStore.getDashboardData()
  } catch (error) {
    toast.error('Failed to load dashboard data')
  } finally {
    isLoading.value = false
  }
})

function notImplemented() {
  toast.error('This feature is not yet implemented')
}

const formatIDR = new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
})
</script>

<template>
  <div class="">
    <!-- greetings -->
    <h1 class="text-3xl font-bold text">
      Welcome,
      <i v-if="isLoading" class="fa-solid fa-spinner fa-spin"></i>
      <span v-else>{{ authStore.dashboard?.user_name || 'User' }}</span>
    </h1>

    <!-- user balance -->
    <div id="user-balance-container" class="w-full mt-8 border border-gray-200 rounded-2xl shadow-md bg-black">
      <div class="flex flex-col px-8 py-12 gap-4 items-start">
        <h3 class="text-white text-lg">Your current balance</h3>
        <!-- nominal row and action button-->
        <span class="flex flex-row items-center gap-6">
          <p class="text-white text-5xl font-bold">
            <i v-if="isLoading" class="fa-solid fa-spinner fa-spin"></i>
            <span v-else>
              {{
                isBalanceVisible ? `${formatIDR.format(authStore.dashboard?.balance)}` : '••••••••'
              }}
            </span>
          </p>
          <button @click="isBalanceVisible = !isBalanceVisible">
            <i class="text-white text-3xl fa-solid" :class="isBalanceVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
          </button>
        </span>

        <h2 class="text-white text-xl font-semibold">
          Wallet Number:
          <i v-if="isLoading" class="fa-solid fa-spinner fa-spin"></i>
          <span v-else>
            {{ isBalanceVisible ? authStore.dashboard?.wallet_number : '••••••' }}
          </span>
        </h2>

        <div class="flex flex-row gap-6">
          <!-- button 1, send money -->
          <div class="flex flex-col items-center gap-2 mt-6">
            <button class="bg-white rounded-2xl p-4" @click="isModalOpen = true">
              <i class="fa-solid fa-paper-plane text-2xl"></i>
            </button>
            <p class="text-white">Send Money</p>
          </div>
          <!-- button 2, add money -->
          <div class="flex flex-col items-center gap-2 mt-6">
            <button class="bg-white rounded-2xl p-4" @click="notImplemented">
              <i class="fa-solid fa-plus text-2xl"></i>
            </button>
            <p class="text-white">Add Money</p>
          </div>
        </div>
      </div>
    </div>

    <SendMoneyModal v-if="isModalOpen" @close="isModalOpen = false" />
  </div>
</template>

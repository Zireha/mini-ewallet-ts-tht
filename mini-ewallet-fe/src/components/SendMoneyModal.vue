<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification';

const emit = defineEmits(['close'])
const authStore = useAuthStore();
const toast = useToast();

const form = ref({
  walletNumber: '',
  amount: '',
})

const isLoading = ref(false)

const isValidAmount = computed(() => {
  return form.value.amount !== "" && Number(form.value.amount) <= 0
})

const isNotSufficient = computed(() => {
  return form.value.amount !== "" && Number(form.value.amount) > Number(authStore.dashboard?.balance || 0)
})

// Check if the receiver wallet number is the user's own wallet number
const isSelfTransfer = computed(() => {
  return form.value.walletNumber !== "" && Number(form.value.walletNumber) === Number(authStore.dashboard?.wallet_number)
})

const isSubmitDisabled = computed(() => {
  return isLoading.value || !form.value.walletNumber || !form.value.amount || isValidAmount.value || isNotSufficient.value || isSelfTransfer.value
})

async function handleSubmit() {
  // if guard for handling the empty
  if (isSubmitDisabled.value) return

  isLoading.value = true
  try {
    await authStore.sendMoney(Number(form.value.walletNumber), Number(form.value.amount))

    await authStore.getDashboardData()
    toast.success('Transfer Success')
    emit('close')
  } catch (error) {
    toast.error(error.message)
  } finally {
    isLoading.value = false
  }
}


</script>

<template>
  <!-- backdrop -->
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="$emit('close')">
    <!-- modal -->
    <div class="w-full max-w-md rounded-2xl shadow-xl">
      <!-- Header -->
      <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-sky-800">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-full bg-black">
            <i class="fa-solid fa-paper-plane text-sm text-white"></i>
          </div>
          <h2 class="text-lg font-semibold text-white">Send Money</h2>
        </div>
        <button class="flex h-8 w-8 items-center justify-center rounded-full text-white hover:bg-gray-100"
          @click="$emit('close')">
          <i class="fa-solid fa-xmark text-white"></i>
        </button>
      </div>

      <!-- form body -->
      <div class="bg-white px-2 py-4 flex flex-col gap-4">
        <div class="flex flex-col gap-1">
          <h4 class="text-lg font-semibold">Amount</h4>
          <input v-model.number="form.amount" placeholder="Input Amount... (e.g: 200000)" type="number"
            class="rounded-md border-2 border-gray-400 w-full active:border-gray-200 px-2 py-2" />
          <p v-if="isValidAmount" class="text-xs text-red-500">Amount must be larger than 0</p>
          <p v-else-if="isNotSufficient" class="text-xs text-red-500">Insufficient Balance</p>
        </div>
        <div class="flex flex-col gap-1">
          <h4 class="text-lg font-semibold">Receiver Wallet Number</h4>
          <input v-model.number="form.walletNumber" placeholder="Receiver Wallet Number" type="number"
            class="rounded-md border-2 border-gray-400 w-full active:border-gray-200 px-2 py-2" />

          <!-- 'can't transfer to yourself' guard -->
          <p v-if="isSelfTransfer" class="text-xs text-red-500">You cannot transfer money to yourself!</p>
        </div>

        <div class="flex flex-row justify-around">
          <button type="button" :disabled="isSubmitDisabled"
            class="rounded-md bg-sky-600 items-center px-12 py-2 text-lg font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed"
            @click="handleSubmit">
            <i v-if="isLoading" class="fa-solid fa-spinner fa-spin mr-2"></i>
            Send
          </button>
          <button type="button" @click="emit('close')"
            class="rounded-md bg-red-600 items-center px-12 py-2 text-lg font-semibold text-white">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

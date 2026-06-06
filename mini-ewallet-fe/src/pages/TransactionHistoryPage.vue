<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()
const isLoading = ref(true)
const currentPage = ref(1)

async function fetchTransactions(page = 1) {
  isLoading.value = true
  try {
    await authStore.getTransactions(page)
    currentPage.value = page
  } catch (error) {
    console.error('Transactions error:', error)
    toast.error('Gagal mengambil data transaksi')
  } finally {
    isLoading.value = false
  }
}

onMounted(() => fetchTransactions())
</script>

<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">Transaction History</h1>

    <!-- Loading -->
    <div v-if="isLoading" class="flex justify-center py-12">
      <i class="fa-solid fa-spinner fa-spin text-3xl text-gray-400"></i>
    </div>

    <!-- Table -->
    <div v-else class="rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
      <table class="w-full text-sm">
        <!-- Header -->
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
          <tr>
            <th class="px-6 py-3 text-left">Transaction ID</th>
            <th class="px-6 py-3 text-left">Description</th>
            <th class="px-6 py-3 text-left">Date</th>
            <th class="px-6 py-3 text-left">Type</th>
            <th class="px-6 py-3 text-right">Amount</th>
          </tr>
        </thead>

        <!-- Body -->
        <tbody class="divide-y divide-gray-100 bg-white">
          <tr v-if="authStore.transactions.length === 0">
            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No Transaction History Yet</td>
          </tr>

          <tr
            v-for="trx in authStore.transactions"
            :key="trx.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="px-6 py-4 font-mono text-gray-600">TXID{{ trx.id }}</td>
            <td class="px-6 py-4 text-gray-800">{{ trx.description }}</td>
            <td class="px-6 py-4 text-gray-500">{{ trx.date }}</td>
            <td class="px-6 py-4">
              <span
                class="px-2 py-1 rounded-full text-xs font-semibold"
                :class="
                  trx.type === 'debit' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'
                "
              >
                {{ trx.type }}
              </span>
            </td>
            <td
              class="px-6 py-4 text-right font-semibold"
              :class="trx.type === 'debit' ? 'text-red-500' : 'text-green-500'"
            >
              {{ trx.type === 'debit' ? '-' : '+' }}{{ Number(trx.amount).toLocaleString('id-ID') }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div
      v-if="!isLoading && authStore.transactionsMeta"
      class="flex items-center justify-between mt-4 px-1"
    >
      <!-- Info -->
      <p class="text-sm text-gray-500">
        Page {{ authStore.transactionsMeta.current_page }} from
        {{ authStore.transactionsMeta.last_page }} &bull; Total
        {{ authStore.transactionsMeta.total }} transactions
      </p>

      <!-- buttons -->
      <div class="flex gap-2">
        <button
          :disabled="currentPage === 1"
          class="flex items-center gap-1 px-4 py-2 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
          @click="fetchTransactions(currentPage - 1)"
        >
          <i class="fa-solid fa-chevron-left"></i>
          Previous
        </button>

        <button
          :disabled="currentPage === authStore.transactionsMeta.last_page"
          class="flex items-center gap-1 px-4 py-2 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
          @click="fetchTransactions(currentPage + 1)"
        >
          Next
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
</template>

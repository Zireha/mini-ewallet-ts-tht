import { defineStore } from 'pinia'
import { ref } from 'vue'

const baseUrl = 'http://127.0.0.1:8000/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)
  const dashboard = ref(null)
  const transactions = ref([])
  const transactionsMeta = ref(null)

  async function login(email, password) {
    const response = await fetch(`${baseUrl}/user/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    })

    if (!response.ok) {
      console.log('error')
      throw new Error('Email or password is wrong')
    }

    const data = await response.json()
    console.log(data.message)
    // store value
    user.value = data.user
    token.value = data.token

    // set token into localStorage

    localStorage.setItem('token', data.token)
  }

  async function sendMoney(receiverWalletNumber, amount) {
    const response = await fetch(`${baseUrl}/user/send`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token.value}`,
      },
      body: JSON.stringify({
        receiver_number: receiverWalletNumber,
        amount: Number(amount),
      }),
    })

    if (!response.ok) {


      const errorData = await response.json()
      
      if(errorData.message === "The selected receiver number is invalid.") {
        throw new Error("Receiver number not found")
      }

      throw new Error(errorData.message)
    }

    return await response.json()
  }

  async function getDashboardData() {
    const response = await fetch(`${baseUrl}/user/dashboard`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token.value}`,
      },
    })

    if (!response.ok) {
      throw new Error('Error fetching data')
    }

    const data = await response.json()
    dashboard.value = data.data
  }

  async function getTransactions(page = 1) {
    const response = await fetch(`${baseUrl}/user/transactions?page=${page}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token.value}`,
      },
    })

    if (!response.ok) {
      throw new Error('Failed fetching transaction history')
    }

    const data = await response.json()
    transactions.value = data.data
    transactionsMeta.value = data.meta
  }

  function logout() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
  }

  return {
    user,
    token,
    login,
    logout,
    sendMoney,
    dashboard,
    getDashboardData,
    transactions,
    transactionsMeta,
    getTransactions,
  }
})

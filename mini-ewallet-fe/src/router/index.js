import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '@/pages/LoginPage.vue'
import HomePage from '@/pages/HomePage.vue'
import TransactionHistoryPage from '@/pages/TransactionHistoryPage.vue'

const routes = [
  { path: '/login', component: LoginPage },
  {
    path: '/',
    component: HomePage,
    meta: { requiresAuth: true }
  },
  { path: '/transactions', component: TransactionHistoryPage, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// params (to, from (unused))
router.beforeEach((to, _from) => {
  const isLoggedIn = !!localStorage.getItem('token')

  if (to.meta.requiresAuth && !isLoggedIn) {
    return { path: '/login' }
  }

  if (to.path === '/login' && isLoggedIn) {
    return { path: '/' }
  }
})

export default router;

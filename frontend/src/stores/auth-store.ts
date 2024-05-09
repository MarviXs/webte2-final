import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
import type { User } from '../models/User'
import AuthService from '@/services/AuthService'
import { computed, ref, watch } from 'vue'
import { nextTick } from 'vue'
import type { UserLogin } from '@/models/Auth'
import router from '@/router'

export const useAuthStore = defineStore('authStore', () => {
  const token = useStorage('token', '')
  const role = useStorage('role', '')
  const user = ref<User | null>(null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(userLogin: UserLogin) {
    const res = await AuthService.login(userLogin)
    token.value = res.token
  }

  function toLogin() {
    router.push('/login')
  }

  function clearToken() {
    token.value = ''
  }

  async function logout() {
    try {
      await AuthService.logout()
    }
    finally {
      clearToken()
    }
  }

  async function getUser(delay = false) {
    if (!isAuthenticated.value) return
    if (delay) await nextTick()
    user.value = await AuthService.getUser()
    role.value = user.value.role
  }
  getUser(true)

  watch(token, async () => {
    if (token.value) {
      getUser()
    } else {
      user.value = null
    }
  })

  return {
    token,
    login,
    user,
    isAuthenticated,
    clearToken,
    toLogin,
    logout,
    getUser,
    role
  }
})

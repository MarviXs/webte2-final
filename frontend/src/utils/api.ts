import { useAuthStore } from '@/stores/auth-store'
import { ofetch, type $Fetch } from 'ofetch'

function onRequest(context: { options: any }) {
  const { options } = context
  const authStore = useAuthStore()

  if (authStore.token) {
    options.headers = {
      ...options.headers,
      Authorization: `Bearer ${authStore.token}`
    }
  }
}

async function onResponseError(context: { response: any }) {
  const { response } = context
  const authStore = useAuthStore()
  if (!response) return

  const authErrors = ['Unauthenticated.']

  if (response.status === 401 && authErrors.includes(response._data?.message)) {
    console.log('Unauthenticated')
    authStore.clearToken()
    authStore.toLogin()
  }
}


const api = <$Fetch>ofetch.create({
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  onRequest,
  onResponseError,
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8011/api',
})

export { api }
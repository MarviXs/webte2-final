import { ofetch, type $Fetch } from 'ofetch'

const api = <$Fetch>ofetch.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8011/api',
})

export { api }
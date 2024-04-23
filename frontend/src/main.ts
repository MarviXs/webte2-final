import '@/assets/app.scss'
import '@/assets/auth.scss'
import App from '@/App.vue'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from '@/router'
import QuasarPlugin from '@/plugins/quasar'
import ToastifyPlugin from '@/plugins/toastify'
import i18nPlugin from '@/plugins/i18n'
import VueApexCharts from 'vue3-apexcharts'

const pinia = createPinia()
const app = createApp(App)

app.use(pinia)
app.use(router)
app.use(QuasarPlugin)
app.use(ToastifyPlugin)
app.use(i18nPlugin)
app.use(VueApexCharts)

app.mount('#app')

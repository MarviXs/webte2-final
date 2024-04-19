import Vue3Toasity, { type ToastContainerOptions } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const toastOptions: ToastContainerOptions = {
  autoClose: 3000,
  clearOnUrlChange: false
}

export default {
  install: (app: any) => {
    app.use(Vue3Toasity, toastOptions)
  }
}

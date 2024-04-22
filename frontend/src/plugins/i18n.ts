import { createI18n } from 'vue-i18n'
import en from '@/i18n/en.json'

const config = {
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en
  }
}

export default {
  install: (app: any) => {
    app.use(createI18n(config))
  }
}

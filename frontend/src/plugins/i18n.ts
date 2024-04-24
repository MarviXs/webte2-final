import { createI18n } from 'vue-i18n'
import en from '@/i18n/en.json'
import sk from '@/i18n/sk.json'

const config = {
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en,
    sk
  }
}

export default {
  install: (app: any) => {
    app.use(createI18n(config))
  }
}

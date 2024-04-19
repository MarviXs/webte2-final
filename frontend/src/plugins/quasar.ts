import { Quasar } from 'quasar'

// Import icon libraries
import '@quasar/extras/roboto-font/roboto-font.css'
import iconSet from 'quasar/icon-set/svg-mdi-v7'

// Import Quasar css
import 'quasar/src/css/index.sass'

// Import Quasar language

const config = {
  iconSet: iconSet,
  plugins: {},
}

export default {
  install: (app: any) => {
    app.use(Quasar, config)
  }
}

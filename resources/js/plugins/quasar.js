import { Quasar, Loading, Dialog, Notify, Cookies } from 'quasar'

import '@quasar/extras/material-icons/material-icons.css'
import 'quasar/src/css/index.sass'
import quasarLang from 'quasar/lang/ru'

const config = {
    plugins: { Loading, Notify, Dialog, Cookies },
    lang: quasarLang,
}

export { Quasar as quasarInstance, config as quasarConfig}
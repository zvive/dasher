import Alpine from 'alpinejs'
import FormsAlpinePlugin from '../../vendor/dasher/forms/dist/module.esm'
import Focus from '@alpinejs/focus'

Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(Focus)

window.Alpine = Alpine

Alpine.start()

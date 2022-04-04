const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

mix.disableSuccessNotifications()
mix.options({
  terser: {
    extractComments: false
  }
})
mix.setPublicPath('packages/core/dist')
mix.setResourceRoot('packages/core/resources')
mix.sourceMaps()
mix.version()

mix.js('packages/core/resources/js/app.js', 'packages/core/dist')

mix
  .postCss('packages/core/resources/css/app.css', 'packages/core/dist', [
    tailwindcss('packages/core/tailwind.config.js')
  ]).options({
    processCssUrls: false
  })

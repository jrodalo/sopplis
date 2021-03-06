const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/assets/js/app.js', 'public/js').extract(['vue', 'vuex', 'vue-router', 'axios', 'lodash.orderby', 'sweetalert', 'laravel-echo', 'pusher-js'])
    .sass('resources/assets/sass/app.scss', 'public/css')
    .version()
    .copyDirectory('resources/assets/images', 'public/images');

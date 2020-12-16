const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    //.js('resources/asset/js/app.js', 'public/js')
    .js('resources/js/common.js', 'public/js/common.js')
    .js('node_modules/dropzone/dist/dropzone.js', 'public/js/app.js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config')).version();
mix.browserSync('localhost:8000');
mix.sass('resources/asset/sass/app.scss', 'public/css/app2.css');
mix.copy('node_modules/font-awesome/fonts', 'public/build/fonts');

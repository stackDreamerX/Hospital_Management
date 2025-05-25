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

// Compile chatbot JavaScript
mix.js('resources/js/chatbot.js', 'public/js')
    .vue({ version: 2 });

// Thêm các tài nguyên khác nếu cần
// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');

// Copy các file hình ảnh nếu cần
// mix.copy('resources/images', 'public/images');

// Nếu trong môi trường production
if (mix.inProduction()) {
    mix.version();
}
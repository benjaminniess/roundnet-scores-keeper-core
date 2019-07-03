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

mix.setPublicPath('htdocs/');
mix.react('resources/js/app.js', 'htdocs/js').sass('resources/sass/app.scss', 'htdocs/css');
mix.react('resources/js/game-live/game-live.js', 'htdocs/js');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}

mix.copyDirectory('node_modules/tinymce/icons', 'public/node_modules/tinymce/icons');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/node_modules/tinymce/plugins');
mix.copyDirectory('node_modules/tinymce/skins', 'public/node_modules/tinymce/skins');
mix.copyDirectory('node_modules/tinymce/themes', 'public/node_modules/tinymce/themes');
mix.copy('node_modules/tinymce/jquery.tinymce.js', 'public/node_modules/tinymce/jquery.tinymce.js');
mix.copy('node_modules/tinymce/jquery.tinymce.min.js', 'public/node_modules/tinymce/jquery.tinymce.min.js');
mix.copy('node_modules/tinymce/tinymce.js', 'public/node_modules/tinymce/tinymce.js');
mix.copy('node_modules/tinymce/tinymce.min.js', 'public/node_modules/tinymce/tinymce.min.js');
mix.copyDirectory('node_modules/jquery/dist', 'public/node_modules/jquery/dist');
mix.copyDirectory('node_modules/cleave.js/dist', 'public/node_modules/cleave.js');
mix.copyDirectory('node_modules/moment/min', 'public/node_modules/moment');
mix.copyDirectory('node_modules/tempusdominus-bootstrap-4/build', 'public/node_modules/tempusdominus-bootstrap-4');
mix.copyDirectory('node_modules/apexcharts/dist', 'public/node_modules/apexcharts');

mix.copy('node_modules/evol-colorpicker/js/evol-colorpicker.min.js', 'public/node_modules/evol-colorpicker/js/evol-colorpicker.min.js');
mix.copy('node_modules/evol-colorpicker/css/evol-colorpicker.min.css', 'public/node_modules/evol-colorpicker/css/evol-colorpicker.min.css');


mix.copy('vendor/proengsoft/laravel-jsvalidation/resources/views', 'resources/views/vendor/jsvalidation')
    .copy('vendor/proengsoft/laravel-jsvalidation/public', 'public/vendor/jsvalidation');

mix.autoload({ 'jquery': ['window.$', 'window.jQuery'] });
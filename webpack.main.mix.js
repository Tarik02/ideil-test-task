const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix
    .js('resources/js/app.js', 'public/js')
    .extract(['vue', 'vuex', 'axios', 'jquery', 'bootstrap', 'lodash', 'popper.js'])
    .mergeManifest()
;

mix
    .sass('resources/sass/app.scss', 'public/css');

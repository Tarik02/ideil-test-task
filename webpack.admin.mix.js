const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

// mix.webpackConfig({
//     // https://vuetifyjs.com/en/getting-started/quick-start/
//     module: {
//         rules: [
//             {
//                 test: /\.s[ca]ss$/,
//                 use: [
//                     'vue-style-loader',
//                     'css-loader',
//                     {
//                         loader: 'sass-loader',
//                         options: {
//                             implementation: require('sass'),
//                             fiber: require('fibers'),
//                         },
//                     },
//                 ],
//             },
//         ],
//     },
// });

mix
    .js('resources/js/admin/app.js', 'public/assets/admin/js')
    .extract(['vue', 'vuex', 'axios', 'vue-router', 'lodash', 'vuetify'])
    .mergeManifest()
;

mix
    .sass('resources/sass/admin/app.scss', 'public/assets/admin/css');

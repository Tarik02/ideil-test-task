// http://www.compulsivecoders.com/tech/how-to-build-multiple-vendors-using-laravel-mix/
require('dotenv').install();
const mix = require('laravel-mix');

const DEFAULT_SECTION = 'main';
const SECTIONS = ['admin', 'main'];

const section = 'SECTION' in process.env ? process.env.SECTION : DEFAULT_SECTION;

if (SECTIONS.includes(section)) {
    require(`${__dirname}/webpack.${section}.mix.js`);
} else {
    console.log(
        '\x1b[41m%s\x1b[0m',
        'Provide correct SECTION environment variable to build command: ' + SECTIONS.join(', ')
    );
    throw new Error('Provide correct SECTION environment variable to build command!');
}

mix.options({
    hmrOptions: {
        host: process.env.APP_URL.match(/^(?:https?:\/\/)?(.*)$/)[1],
        port: 8080,
    },
});

mix.sourceMaps(false);

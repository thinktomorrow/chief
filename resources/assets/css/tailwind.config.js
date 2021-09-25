const defaultColors = require('tailwindcss/colors');

const PurgeCssConfig = require('../../../purgecss.config.js');
const WarpaintRow = require('./warpaint/utilities/WarpaintRow.js');
const WarpaintContainer = require('./warpaint/utilities/WarpaintContainer.js');
const WarpaintGutter = require('./warpaint/utilities/WarpaintGutter.js');

module.exports = {
    mode: 'jit',
    purge: [
        'resources/views/**/*.blade.php',
        'resources/assets/**/*.js',
        'resources/assets/**/*.vue',
        'resources/assets/css/components/slim.scss',
        'resources/assets/css/components/multiselect.scss',
        'node_modules/vue-multiselect/dist/vue-multiselect.min.css',
        'resources/assets/css/components/redactor.scss',
    ],
    theme: {
        screens: {
            xs: '480px',
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1536px',
        },
        fontFamily: {
            display: ['Inter'],
            body: ['Inter'],
        },
        colors: {
            current: 'currentColor',
            transparent: 'transparent',

            white: defaultColors.white,
            grey: defaultColors.blueGray,
            black: defaultColors.black,

            // primary: defaultColors.blue,
            // secondary: defaultColors.teal,
            //
            primary: {
                50: '#f6f9fb',
                100: '#e3effd',
                200: '#c8d6fb',
                300: '#a2b2f5',
                400: '#848aed',
                500: '#6d66e8',
                600: '#5a49dd',
                700: '#4537c0',
                800: '#302693',
                900: '#1b185e',
            },
            secondary: {
                50: '#fdfcfb',
                100: '#fcf0ed',
                200: '#f9cdda',
                300: '#f09fb6',
                400: '#ee6e8e',
                500: '#e44a6e',
                600: '#cf314e',
                700: '#a92539',
                800: '#7d1a25',
                900: '#4d1114',
            },

            // For notifications, warnings, errors ...
            red: defaultColors.red,
            green: defaultColors.green,
            blue: defaultColors.lightBlue,
            orange: defaultColors.orange,
        },
        extend: {
            borderRadius: {
                window: '0.5rem',
            },
            lineHeight: {
                0: '0',
            },
            maxHeight: {
                '1/2': '50vh',
            },
            minWidth: {
                xs: '20rem',
                sm: '24rem',
                md: '28rem',
                lg: '32rem',
                xl: '36rem',
            },
            scale: {
                65: '0.65',
            },
            spacing: {
                96: '24rem',
                128: '32rem',
                160: '40rem',
                192: '48rem',
            },
            zIndex: {
                1: '1',
            },
        },
    },
    corePlugins: {
        container: false,
    },
    plugins: [WarpaintRow, WarpaintContainer, WarpaintGutter],
    variants: {
        extend: {
            scale: ['group-hover'],
        },
    },
};

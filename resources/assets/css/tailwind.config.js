const colors = require('tailwindcss/colors');

const PurgeCssConfig = require('../../../purgecss.config.js');
const WarpaintRow = require('./warpaint/utilities/WarpaintRow.js');
const WarpaintContainer = require('./warpaint/utilities/WarpaintContainer.js');
const WarpaintGutter = require('./warpaint/utilities/WarpaintGutter.js');

module.exports = {
    purge: PurgeCssConfig,
    theme: {
        screens: {
            xs: '480px',
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1536px',
        },
        colors: {
            current: 'currentColor',
            transparent: 'transparent',

            white: colors.white,
            grey: colors.blueGray,
            black: colors.black,

            primary: colors.blue,
            secondary: colors.teal,

            red: colors.red,
            green: colors.green,
            blue: colors.lightBlue,
            orange: colors.orange,
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

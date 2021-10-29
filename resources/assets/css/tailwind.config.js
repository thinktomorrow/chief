const colors = require('tailwindcss/colors');

module.exports = {
    mode: 'jit',
    purge: [
        'resources/views/**/*.blade.php',
        'resources/assets/**/*.js',
        'resources/assets/**/*.vue',
        'resources/assets/css/components/slim.scss',
        'resources/assets/css/components/redactor.scss',
        'resources/assets/css/components/multiselect.scss',
        'node_modules/vue-multiselect/dist/vue-multiselect.min.css',
        'src/Addons/Repeat/resources/views/**/*.blade.php',
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
        colors: {
            current: 'currentColor',
            transparent: 'transparent',

            white: colors.white,
            grey: colors.trueGray,
            black: colors.black,

            primary: colors.indigo,
            secondary: colors.teal,

            red: colors.red,
            green: colors.green,
            blue: colors.lightBlue,
            orange: colors.orange,
        },
        fontFamily: {
            display: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
            body: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
        },
        extend: {
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
            spacing: {
                96: '24rem',
                128: '32rem',
                160: '40rem',
                192: '48rem',
            },
        },
    },
    corePlugins: {
        container: false,
    },
    plugins: [
        require('./warpaint/ProseSpacing'),
        require('./warpaint/WarpaintSpacing'),
        require('./warpaint/WarpaintContainer'),
        require('./warpaint/WarpaintGutter'),
        require('./warpaint/WarpaintRow'),
    ],
};

const colors = require('tailwindcss/colors');

module.exports = {
    content: [
        'resources/views/**/*.blade.php',
        'resources/assets/**/*.js',
        'resources/assets/**/*.vue',
        'resources/assets/css/components/slim.scss',
        'resources/assets/css/components/redactor.scss',
        'resources/assets/css/components/multiselect.scss',
        'node_modules/vue-multiselect/dist/vue-multiselect.min.css',
        'src/Forms/resources/**/*.blade.php',
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
            grey: colors.slate,
            black: colors.black,

            primary: colors.indigo,
            secondary: colors.teal,

            red: colors.red,
            green: colors.green,
            blue: colors.sky,
            orange: colors.orange,
        },
        fontFamily: {
            display: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
            body: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
        },
        extend: {
            animation: {
                'slide-in-nav': 'slideInNavigation 400ms cubic-bezier(0.83, 0, 0.17, 1)',
            },
            keyframes: {
                slideInNavigation: {
                    '0%': { transform: 'translateX(100vw)' },
                    '100%': { transform: 'translateX(0)' },
                },
            },
            maxHeight: {
                '1/2': '50vh',
            },
            minWidth: {
                32: '8rem',
                48: '12rem',
                64: '16rem',
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

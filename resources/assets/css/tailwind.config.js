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
        'src/Forms/Layouts/**/*.php',
        'src/Forms/Concerns/**/*.php',
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
            // red: {
            //     50: '#fdf3f6',
            //     100: '#fae7ed',
            //     200: '#f4c4d2',
            //     300: '#eda0b6',
            //     400: '#df5880',
            //     500: '#d11149',
            //     600: '#bc0f42',
            //     700: '#9d0d37',
            //     800: '#7d0a2c',
            //     900: '#660824',
            // },

            green: {
                50: '#f3fcf6',
                100: '#e7faed',
                200: '#c2f2d2',
                300: '#9eeab7',
                400: '#55da80',
                500: '#0cca4a',
                600: '#0bb643',
                700: '#099838',
                800: '#07792c',
                900: '#066324',
            },

            // blue: colors.sky,
            blue: {
                50: '#f3fdff',
                100: '#e7faff',
                200: '#c2f3ff',
                300: '#9debff',
                400: '#54dcff',
                500: '#0acdff',
                600: '#09b9e6',
                700: '#089abf',
                800: '#067b99',
                900: '#05647d',
            },

            orange: colors.orange,
            // orange: {
            //     50: '#fef9f7',
            //     100: '#fdf3ee',
            //     200: '#fae0d5',
            //     300: '#f7cdbc',
            //     400: '#f1a88a',
            //     500: '#eb8258',
            //     600: '#d4754f',
            //     700: '#b06242',
            //     800: '#8d4e35',
            //     900: '#73402b',
            // },
        },
        fontFamily: {
            display: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
            body: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
        },
        extend: {
            animation: {
                'slide-in-nav': 'slideInNavigation 400ms cubic-bezier(0.83, 0, 0.17, 1)',
                'pop-in': 'popIn 200ms cubic-bezier(0.83, 0, 0.17, 1)',
                'pop-in-out': 'popInOut 2000ms cubic-bezier(0.83, 0, 0.17, 1)',
            },
            boxShadow: {
                card: '0 4px 6px -1px rgba(0, 0, 0, 0.025)',
            },
            keyframes: {
                slideInNavigation: {
                    '0%': { transform: 'translateX(100vw)' },
                    '100%': { transform: 'translateX(0)' },
                },
                popIn: {
                    '0%': { transform: 'scale(0)' },
                    '100%': { transform: 'scale(1)' },
                },
                popInOut: {
                    '0%': { transform: 'scale(0)' },
                    '10%, 90%': { transform: 'scale(1)' },
                    '100%': { transform: 'scale(0)' },
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

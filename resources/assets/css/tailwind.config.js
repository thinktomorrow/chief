const colors = require('tailwindcss/colors');

module.exports = {
    content: [
        'resources/views/**/*.blade.php',
        'resources/assets/**/*.js',
        'resources/assets/**/*.vue',
        'resources/assets/css/components/redactor.scss',
        'src/Forms/resources/**/*.blade.php',
        'src/Forms/Layouts/**/*.php',
        'src/Forms/Concerns/**/*.php',
        'src/Fragments/resources/**/*.blade.php',
        'src/Table/resources/**/*.blade.php',
        'src/Plugins/**/resources/**/*.blade.php',
        'src/Plugins/HotSpots/views/**/*.blade.php',
        'src/Assets/App/resources/**/*.blade.php',
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
            grey: colors.gray,
            black: colors.black,
            primary: colors.indigo,
            secondary: colors.teal,
            red: colors.red,
            orange: colors.orange,
            green: colors.green,
            blue: colors.sky,
        },
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                lg: '3rem',
            },
        },
        fontFamily: {
            display: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
            body: ['Inter', 'Helvetica', 'Arial', 'sans-serif'],
        },
        fontSize: {
            xs: ['0.75rem', { lineHeight: '1rem' }],
            sm: ['0.875rem', { lineHeight: '1.25rem' }],
            base: ['1rem', { lineHeight: '1.5rem' }],
            lg: ['1.125rem', { lineHeight: '1.75rem' }],
            xl: ['1.25rem', { lineHeight: '1.75rem' }],
            '2xl': ['1.5rem', { lineHeight: '2rem' }],
            '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
            '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
            '5xl': ['3rem', { lineHeight: '1' }],
            '6xl': ['3.75rem', { lineHeight: '1' }],
            '7xl': ['4.5rem', { lineHeight: '1' }],
            '8xl': ['6rem', { lineHeight: '1' }],
            '9xl': ['8rem', { lineHeight: '1' }],
        },
        extend: {
            animation: {
                'slide-in-nav': 'slideInNavigation 400ms cubic-bezier(0.83, 0, 0.17, 1)',
                'pop-in': 'popIn 200ms cubic-bezier(0.83, 0, 0.17, 1)',
                'pop-in-tag': 'popIn 100ms cubic-bezier(0.83, 0, 0.17, 1)',
                'pop-in-out': 'popInOut 2000ms cubic-bezier(0.83, 0, 0.17, 1)',
                'dialog-pop-in': 'dialogPopIn 150ms ease-out',
                'dialog-fade-in': 'dialogFadeIn 150ms ease-out',
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
                dialogPopIn: {
                    '0%': { transform: 'scale(0.9)' },
                    '100%': { transform: 'scale(1)' },
                },
                dialogFadeIn: {
                    '0%': { opacity: 0 },
                    '100%': { opacity: 1 },
                },
            },
            boxShadow: {
                card: '0 4px 6px -1px rgba(0, 0, 0, 0.025)',
            },
            spacing: {
                96: '24rem',
                128: '32rem',
                160: '40rem',
                xs: '480px',
                sm: '640px',
                md: '768px',
                lg: '1024px',
                xl: '1280px',
                '2xl': '1536px',
            },
            containers: {
                // To be used for default container styles. This way, if container queries aren't supported, nothing breaks.
                '1px': '1px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/container-queries'),
        require('./warpaint/ProseSpacing'),
        require('./warpaint/WarpaintSpacing'),
        require('./warpaint/WarpaintGutter'),
        require('./warpaint/WarpaintRow'),
    ],
};

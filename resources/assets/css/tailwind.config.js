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
            transparent: colors.transparent,
            black: colors.black,
            white: colors.white,
            primary: colors.blue,
            secondary: colors.teal,
            tertiary: colors.orange,
            // grey: {
            //     50: '#fafafa',
            //     100: '#f5f5f5',
            //     150: '#ededed',
            //     200: '#e5e5e5',
            //     300: '#d4d4d4',
            //     400: '#a3a3a3',
            //     500: '#737373',
            //     600: '#525252',
            //     700: '#404040',
            //     800: '#262626',
            //     900: '#171717',
            // },

            grey: {
                ...colors.coolGray,
                // 100: '#F3F4F6',
                150: '#F0F2F7',
                // 150: colors.coolGray['100'],
                // 200: '#E5E7EB',
            },

            red: colors.red,
            green: colors.green,
            blue: colors.blue,
            orange: colors.orange,

            // TODO: replace by default colors
            success: colors.green['500'],
            warning: colors.orange['500'],
            error: colors.red['500'],
            information: colors.blue['500'],
        },
        extend: {
            zIndex: {
                1: '1',
            },
            lineHeight: {
                0: '0',
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

// const PurgeCssConfig = require('../../../purgecss.config.js');

// module.exports = {
//     purge: PurgeCssConfig,
//     prefix: '',
//     important: false,
//     separator: ':',
//     theme: {
//         screens: {
//             sm: '640px',
//             md: '768px',
//             lg: '1024px',
//             xl: '1280px',
//             '2xl': '1536px',
//         },
//         colors: {
//             transparent: 'transparent',
//             black: '#000',
//             white: '#fff',

//             success: '#7dc68c',
//             warning: '#ffb355',
//             error: '#df5959',
//             information: '#4fa6db',

//             // primary: {
//             //     100: '#E8F8F4',
//             //     200: '#C5EDE3',
//             //     300: '#A2E3D3',
//             //     400: '#5DCDB1',
//             //     500: '#17B890',
//             //     600: '#15A682',
//             //     700: '#0E6E56',
//             //     800: '#0A5341',
//             //     900: '#07372B',
//             // },

//             // primary: {
//             //     50: '#eff6ff',
//             //     100: '#dbeafe',
//             //     200: '#bfdbfe',
//             //     300: '#93c5fd',
//             //     400: '#60a5fa',
//             //     500: '#3b82f6',
//             //     600: '#2563eb',
//             //     700: '#1d4ed8',
//             //     800: '#1e40af',
//             //     900: '#1e3a8a',
//             // },

//             primary: {
//                 50: '#eef2ff',
//                 100: '#e0e7ff',
//                 200: '#c7d2fe',
//                 300: '#a5b4fc',
//                 400: '#818cf8',
//                 500: '#6366f1',
//                 600: '#4f46e5',
//                 700: '#4338ca',
//                 800: '#3730a3',
//                 900: '#312e81',
//             },

//             // primary: {
//             //     50: '#f0fdfa',
//             //     100: '#ccfbf1',
//             //     200: '#99f6e4',
//             //     300: '#5eead4',
//             //     400: '#2dd4bf',
//             //     500: '#14b8a6',
//             //     600: '#0d9488',
//             //     700: '#0f766e',
//             //     800: '#115e59',
//             //     900: '#134e4a',
//             // },

//             secondary: {
//                 50: '#fdf6f5',
//                 100: '#FEF4F2',
//                 200: '#FCE4DD',
//                 300: '#FAD3C9',
//                 400: '#F6B2A1',
//                 500: '#F29178',
//                 600: '#DA836C',
//                 700: '#915748',
//                 800: '#6D4136',
//                 900: '#492C24',
//             },

//             tertiary: {
//                 100: '#FFFFFA',
//                 200: '#FFFFF3',
//                 300: '#FFFEEC',
//                 400: '#FFFEDE',
//                 500: '#FFFDD0',
//                 600: '#E6E4BB',
//                 700: '#99987D',
//                 800: '#73725E',
//                 900: '#4D4C3E',
//             },

//             // grey: {
//             //     50: '#F9FAFB',
//             //     100: '#F3F4F6',
//             //     200: '#E5E7EB',
//             //     300: '#D1D5DB',
//             //     400: '#9CA3AF',
//             //     500: '#6B7280',
//             //     600: '#4B5563',
//             //     700: '#374151',
//             //     800: '#1F2937',
//             //     900: '#111827'
//             // },

//             // grey: {
//             //     50: '#fafaf9',
//             //     100: '#f5f5f4',
//             //     200: '#e7e5e4',
//             //     300: '#d6d3d1',
//             //     400: '#a8a29e',
//             //     500: '#78716c',
//             //     600: '#57534e',
//             //     700: '#44403c',
//             //     800: '#292524',
//             //     900: '#1c1917',
//             // },

//             grey: {
//                 50: '#fafafa',
//                 100: '#f5f5f5',
//                 150: '#ededed',
//                 200: '#e5e5e5',
//                 300: '#d4d4d4',
//                 400: '#a3a3a3',
//                 500: '#737373',
//                 600: '#525252',
//                 700: '#404040',
//                 800: '#262626',
//                 900: '#171717',
//             },

//             // grey: {
//             //     50: '#f7f7f7',
//             //     100: '#EFECEE',
//             //     200: '#D6D0D5',
//             //     300: '#BEB4BB',
//             //     400: '#8D7C89',
//             //     500: '#5C4456',
//             //     600: '#533D4D',
//             //     700: '#372934',
//             //     800: '#291F27',
//             //     900: '#1C141A',
//             // },
//         },
//         spacing: {
//             px: '1px',
//             0: '0',
//             1: '0.25rem',
//             2: '0.5rem',
//             3: '0.75rem',
//             4: '1rem',
//             5: '1.25rem',
//             6: '1.5rem',
//             8: '2rem',
//             10: '2.5rem',
//             12: '3rem',
//             16: '4rem',
//             20: '5rem',
//             24: '6rem',
//             32: '8rem',
//             40: '10rem',
//             48: '12rem',
//             56: '14rem',
//             64: '16rem',
//             80: '20rem',
//             88: '22rem',
//             96: '24rem',
//             peak: 'calc(100% - 4rem - 24px)',
//         },
//         backgroundColor: (theme) => theme('colors'),
//         backgroundPosition: {
//             bottom: 'bottom',
//             center: 'center',
//             left: 'left',
//             'left-bottom': 'left bottom',
//             'left-top': 'left top',
//             right: 'right',
//             'right-bottom': 'right bottom',
//             'right-top': 'right top',
//             top: 'top',
//         },
//         backgroundSize: {
//             auto: 'auto',
//             cover: 'cover',
//             contain: 'contain',
//         },
//         borderColor: (theme) => ({
//             ...theme('colors'),
//             default: theme('colors.gray.300', 'currentColor'),
//         }),
//         borderRadius: {
//             none: '0',
//             sm: '0.125rem',
//             default: '0.25rem',
//             lg: '0.5rem',
//             xl: '0.75rem',
//             '2xl': '1rem',
//             '3xl': '1.5rem',
//             '4xl': '2rem',
//             full: '9999px',
//         },
//         borderWidth: {
//             default: '1px',
//             0: '0',
//             2: '2px',
//             4: '4px',
//             8: '8px',
//         },
//         boxShadow: {
//             default: '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
//             md: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
//             lg: '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
//             xl: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
//             '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
//             inner: 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
//             outline: '0 0 0 3px rgba(66, 153, 225, 0.5)',
//             soft: 'rgba(149, 157, 165, 0.1) 0px 6px 18px',
//             none: 'none',
//         },
//         cursor: {
//             auto: 'auto',
//             default: 'default',
//             pointer: 'pointer',
//             wait: 'wait',
//             text: 'text',
//             move: 'move',
//             'not-allowed': 'not-allowed',
//         },
//         fill: {
//             current: 'currentColor',
//         },
//         flex: {
//             1: '1 1 0%',
//             auto: '1 1 auto',
//             initial: '0 1 auto',
//             none: 'none',
//         },
//         flexGrow: {
//             0: '0',
//             default: '1',
//         },
//         flexShrink: {
//             0: '0',
//             default: '1',
//         },
//         fontFamily: {
//             sans: ['Quicksand'],
//             serif: ['Georgia', 'Cambria', '"Times New Roman"', 'Times', 'serif'],
//             mono: ['Menlo', 'Monaco', 'Consolas', '"Liberation Mono"', '"Courier New"', 'monospace'],
//         },
//         fontSize: {
//             xs: '0.75rem',
//             sm: '0.875rem',
//             base: '1rem',
//             lg: '1.125rem',
//             xl: '1.25rem',
//             '2xl': '1.5rem',
//             '3xl': '1.875rem',
//             '4xl': '2.25rem',
//             '5xl': '3rem',
//             '6xl': '4rem',
//         },
//         fontWeight: {
//             hairline: '100',
//             thin: '200',
//             light: '300',
//             normal: '400',
//             medium: '500',
//             semibold: '600',
//             bold: '700',
//             extrabold: '800',
//             black: '900',
//         },
//         height: (theme) => ({
//             auto: 'auto',
//             px: '1px',
//             ...theme('spacing'),
//             full: '100%',
//             screen: '100vh',
//         }),
//         inset: {
//             0: '0',
//             auto: 'auto',
//         },
//         letterSpacing: {
//             tighter: '-0.05em',
//             tight: '-0.025em',
//             normal: '0',
//             wide: '0.015em',
//             wider: '0.05em',
//             widest: '0.1em',
//         },
//         lineHeight: {
//             none: '1',
//             tight: '1.25',
//             snug: '1.375',
//             normal: '1.5',
//             relaxed: '1.625',
//             loose: '2',
//         },
//         listStyleType: {
//             none: 'none',
//             disc: 'disc',
//             decimal: 'decimal',
//         },
//         margin: (theme, { negative }) => ({
//             auto: 'auto',
//             ...theme('spacing'),
//             ...negative(theme('spacing')),
//         }),
//         maxHeight: {
//             full: '100%',
//             '3/4': '75vh',
//             screen: '100vh',
//         },
//         maxWidth: {
//             xs: '20rem',
//             sm: '24rem',
//             md: '28rem',
//             lg: '32rem',
//             xl: '36rem',
//             '2xl': '42rem',
//             '3xl': '48rem',
//             '4xl': '56rem',
//             '5xl': '64rem',
//             '6xl': '72rem',
//             full: '100%',
//         },
//         minHeight: {
//             0: '0',
//             2: '0.5rem',
//             3: '0.75rem',
//             full: '100%',
//             screen: '100vh',
//         },
//         minWidth: {
//             0: '0',
//             2: '0.5rem',
//             3: '0.75rem',
//             xs: '20rem',
//             sm: '24rem',
//             md: '28rem',
//             lg: '32rem',
//             xl: '36rem',
//             full: '100%',
//         },
//         objectPosition: {
//             bottom: 'bottom',
//             center: 'center',
//             left: 'left',
//             'left-bottom': 'left bottom',
//             'left-top': 'left top',
//             right: 'right',
//             'right-bottom': 'right bottom',
//             'right-top': 'right top',
//             top: 'top',
//         },
//         opacity: {
//             0: '0',
//             25: '0.25',
//             50: '0.5',
//             75: '0.75',
//             100: '1',
//         },
//         order: {
//             first: '-9999',
//             last: '9999',
//             none: '0',
//             1: '1',
//             2: '2',
//             3: '3',
//             4: '4',
//             5: '5',
//             6: '6',
//             7: '7',
//             8: '8',
//             9: '9',
//             10: '10',
//             11: '11',
//             12: '12',
//         },
//         padding: (theme) => theme('spacing'),
//         stroke: {
//             current: 'currentColor',
//         },
//         textColor: (theme) => theme('colors'),
//         width: (theme) => ({
//             auto: 'auto',
//             ...theme('spacing'),
//             '1/2': '50%',
//             '1/3': '33.33333%',
//             '2/3': '66.66667%',
//             '1/4': '25%',
//             '2/4': '50%',
//             '3/4': '75%',
//             '1/5': '20%',
//             '2/5': '40%',
//             '3/5': '60%',
//             '4/5': '80%',
//             '1/6': '16.66667%',
//             '2/6': '33.33333%',
//             '3/6': '50%',
//             '4/6': '66.66667%',
//             '5/6': '83.33333%',
//             '1/12': '8.33333%',
//             '2/12': '16.66667%',
//             '3/12': '25%',
//             '4/12': '33.33333%',
//             '5/12': '41.66667%',
//             '6/12': '50%',
//             '7/12': '58.33333%',
//             '8/12': '66.66667%',
//             '9/12': '75%',
//             '10/12': '83.33333%',
//             '11/12': '91.66667%',
//             full: '100%',
//             screen: '100vw',
//         }),
//         zIndex: {
//             auto: 'auto',
//             0: '0',
//             1: '1',
//             2: '2',
//             10: '10',
//             20: '20',
//             30: '30',
//             40: '40',
//             50: '50',
//         },
//     },
//     variants: {
//         alignContent: ['responsive'],
//         alignItems: ['responsive'],
//         alignSelf: ['responsive'],
//         appearance: ['responsive'],
//         backgroundAttachment: ['responsive'],
//         backgroundColor: ['responsive', 'hover', 'focus'],
//         backgroundPosition: ['responsive'],
//         backgroundRepeat: ['responsive'],
//         backgroundSize: ['responsive'],
//         borderCollapse: ['responsive'],
//         borderColor: ['responsive', 'hover', 'focus'],
//         borderRadius: ['responsive'],
//         borderStyle: ['responsive'],
//         borderWidth: ['responsive'],
//         boxShadow: ['responsive', 'hover', 'focus'],
//         cursor: ['responsive'],
//         display: ['responsive', 'group-hover'],
//         fill: ['responsive'],
//         flex: ['responsive'],
//         flexDirection: ['responsive'],
//         flexGrow: ['responsive'],
//         flexShrink: ['responsive'],
//         flexWrap: ['responsive'],
//         float: ['responsive'],
//         fontFamily: ['responsive'],
//         fontSize: ['responsive'],
//         fontSmoothing: ['responsive'],
//         fontStyle: ['responsive'],
//         fontWeight: ['responsive', 'hover', 'focus'],
//         height: ['responsive'],
//         inset: ['responsive'],
//         justifyContent: ['responsive'],
//         letterSpacing: ['responsive'],
//         lineHeight: ['responsive'],
//         listStylePosition: ['responsive'],
//         listStyleType: ['responsive'],
//         margin: ['responsive'],
//         maxHeight: ['responsive'],
//         maxWidth: ['responsive'],
//         minHeight: ['responsive'],
//         minWidth: ['responsive'],
//         objectFit: ['responsive'],
//         objectPosition: ['responsive'],
//         opacity: ['responsive'],
//         order: ['responsive'],
//         outline: ['responsive', 'focus'],
//         overflow: ['responsive'],
//         padding: ['responsive'],
//         pointerEvents: ['responsive'],
//         position: ['responsive'],
//         resize: ['responsive'],
//         stroke: ['responsive'],
//         tableLayout: ['responsive'],
//         textAlign: ['responsive'],
//         textColor: ['responsive', 'hover', 'focus'],
//         textDecoration: ['responsive', 'hover', 'focus'],
//         textTransform: ['responsive'],
//         userSelect: ['responsive'],
//         verticalAlign: ['responsive'],
//         visibility: ['responsive'],
//         whitespace: ['responsive'],
//         width: ['responsive', 'group-hover'],
//         wordBreak: ['responsive'],
//         zIndex: ['responsive'],
//         scale: ['responsive', 'group-hover'],
//     },
//     corePlugins: {
//         container: false,
//     },
//     plugins: [],
// };

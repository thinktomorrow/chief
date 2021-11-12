const plugin = require('tailwindcss/plugin');

/**
 * Documentation on making a tailwindcss component:
 * - https://tailwindcss.com/docs/plugins#adding-components
 * - https://tailwindcss.com/docs/extracting-components#writing-a-component-plugin
 */
const WarpaintSpacing = plugin(({ addComponents, theme }) => {
    const output = {};
    const breakpoints = theme('screens');
    const spacePerBreakpoint = {
        DEFAULT: '2rem',
        xs: '2rem',
        sm: '3rem',
        md: '3rem',
        lg: '4rem',
        xl: '6rem',
        '2xl': '8rem',
    };
    const selectors = [
        { name: 't', directions: ['top'] },
        { name: 'r', directions: ['right'] },
        { name: 'b', directions: ['bottom'] },
        { name: 'l', directions: ['left'] },
        { name: 'x', directions: ['right', 'left'] },
        { name: 'y', directions: ['top', 'bottom'] },
        { name: '', directions: ['top', 'right', 'bottom', 'left'] },
    ];

    selectors.forEach((selector) => {
        output[`.p${selector.name}`] = generatePropertiesObject('padding', selector, spacePerBreakpoint.DEFAULT);
        output[`.m${selector.name}`] = generatePropertiesObject('margin', selector, spacePerBreakpoint.DEFAULT);
        output[`.-m${selector.name}`] = generatePropertiesObject('margin', selector, `-${spacePerBreakpoint.DEFAULT}`);

        for (const screen in breakpoints) {
            if (spacePerBreakpoint.hasOwnProperty(screen)) {
                const mediaQuery = `@media (min-width: ${breakpoints[screen]})`;

                output[`.p${selector.name}`][mediaQuery] = generatePropertiesObject(
                    'padding',
                    selector,
                    spacePerBreakpoint[screen]
                );

                output[`.m${selector.name}`][mediaQuery] = generatePropertiesObject(
                    'margin',
                    selector,
                    spacePerBreakpoint[screen]
                );

                output[`.-m${selector.name}`][mediaQuery] = generatePropertiesObject(
                    'margin',
                    selector,
                    `-${spacePerBreakpoint[screen]}`
                );
            }
        }
    });

    addComponents({
        ...output,
    });
});

const generatePropertiesObject = (propertyName, selector, value) => {
    const properties = {};

    selector.directions.forEach((direction) => {
        properties[`${propertyName}${ucfirst(direction)}`] = value;
    });

    return properties;
};

const ucfirst = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};

module.exports = WarpaintSpacing;

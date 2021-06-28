const plugin = require('tailwindcss/plugin');

/**
 * A Warpaint-like gutter based on the spacing property from tailwind.config.js.
 * These utilities are generated to work with the breakpoint variants (ex. gutter-4 sm:gutter-6 md:gutter-8...)
 */
const WarpaintGutter = plugin(({ addUtilities, config }) => {
    const spacing = config('theme.spacing');
    const gutterUtilities = {};

    for (const [key, value] of Object.entries(spacing)) {
        const className = `.gutter-${key}`;
        const classProperties = {
            'margin-top': `-${value}`,
            'margin-right': `-${value}`,
            'margin-bottom': `-${value}`,
            'margin-left': `-${value}`,
        };
        const classNameChildren = `.gutter-${key} > *`;
        const classPropertiesChildren = {
            'padding-top': value,
            'padding-right': value,
            'padding-bottom': value,
            'padding-left': value,
        };
        gutterUtilities[className] = classProperties;
        gutterUtilities[classNameChildren] = classPropertiesChildren;
    }

    addUtilities(gutterUtilities, ['responsive']);
});

module.exports = WarpaintGutter;

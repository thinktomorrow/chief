const plugin = require('tailwindcss/plugin');

/**
 * A Warpaint-like gutter based on the spacing property from tailwind.config.js.
 * These utilities are generated to work with the breakpoint variants (ex. gutter-4 sm:gutter-6 md:gutter-8...)
 */
const WarpaintGutter = plugin(function ({ addUtilities, config }) {
    const spacing = config('theme.spacing');
    let gutterUtilities = {};

    for (let [key, value] of Object.entries(spacing)) {
        let className = `.gutter-${key}`;
        let classProperties = {
            'margin-top': `-${value}`,
            'margin-right': `-${value}`,
            'margin-bottom': `-${value}`,
            'margin-left': `-${value}`,
        };
        let classNameChildren = `.gutter-${key} > *`;
        let classPropertiesChildren = {
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

const plugin = require('tailwindcss/plugin');

/**
 * A Warpaint-like gutter based on the spacing property from tailwind.config.js.
 * These utilities are generated to work with the breakpoint variants (ex. gutter-4 sm:gutter-6 md:gutter-8...)
 */
const WarpaintGutter = plugin(({ addUtilities, config }) => {
    const spacing = config('theme.spacing');
    const gutterUtilities = {};

    for (const [key, value] of Object.entries(spacing)) {
        // gutter-* classes
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

        // gutter-x-* classes
        const classNameX = `.gutter-x-${key}`;
        const classPropertiesX = {
            'margin-right': `-${value}`,
            'margin-left': `-${value}`,
        };
        const classNameChildrenX = `.gutter-x-${key} > *`;
        const classPropertiesChildrenX = {
            'padding-right': value,
            'padding-left': value,
        };
        gutterUtilities[classNameX] = classPropertiesX;
        gutterUtilities[classNameChildrenX] = classPropertiesChildrenX;

        // gutter-y-* classes
        const classNameY = `.gutter-y-${key}`;
        const classPropertiesY = {
            'margin-top': `-${value}`,
            'margin-bottom': `-${value}`,
        };
        const classNameChildrenY = `.gutter-y-${key} > *`;
        const classPropertiesChildrenY = {
            'padding-top': value,
            'padding-bottom': value,
        };
        gutterUtilities[classNameY] = classPropertiesY;
        gutterUtilities[classNameChildrenY] = classPropertiesChildrenY;
    }

    addUtilities(gutterUtilities, ['responsive']);
});

module.exports = WarpaintGutter;

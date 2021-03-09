const plugin = require('tailwindcss/plugin');

/**
 * A Warpaint-like container based on the width property from tailwind.config.js.
 * The container is always centered and has a max-width set to the largest screen width from tailwind.config.js
 * These utilities are generated to work with the breakpoint variants (ex. container-1/2 sm:container-4/5 ...)
 * An example output style:
 * .container-4/5 {
 *     margin-left: auto;
 *     margin-right: auto;
 *     width: 80%;
 *     max-width: 1440px; }
 */
const WarpaintContainer = plugin(function ({ addUtilities, config }) {
    const widths = config('theme.width');
    const breakpoints = config('theme.screens');

    let containerUtilities = {};

    for (let [key, value] of Object.entries(widths)) {
        let className = `.container-${key.split('/').join('\\/')}`;
        let classProperties = {
            'margin-left': 'auto',
            'margin-right': 'auto',
            width: `${value}`,
            'max-width': breakpoints[Object.keys(breakpoints)[Object.keys(breakpoints).length - 1]],
        };
        containerUtilities[className] = classProperties;
    }

    addUtilities(containerUtilities, ['responsive']);
});

module.exports = WarpaintContainer;

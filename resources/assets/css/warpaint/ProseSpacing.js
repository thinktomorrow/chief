const plugin = require('tailwindcss/plugin');

/**
 * Documentation on making a tailwindcss component:
 * - https://tailwindcss.com/docs/plugins#adding-components
 * - https://tailwindcss.com/docs/extracting-components#writing-a-component-plugin
 */
const ProseSpacing = plugin(({ addComponents, theme }) => {
    const elementsLoose = ['h1', 'h2', 'h3'];
    const elementsRelaxed = ['h4', 'h5', 'h6'];
    const elementsNormal = ['p', 'ol', 'ul', 'blockquote', 'code', 'img', 'iframe', 'form', 'table'];
    const elementsTight = ['figcaption', 'cite', 'li'];

    const output = {};

    [...elementsLoose, ...elementsRelaxed, ...elementsNormal, ...elementsTight].forEach((element) => {
        elementsLoose.forEach((subElement) => {
            output[`${element} + ${subElement}`] = {
                marginTop: '1.5rem',
                [`@media (min-width: ${theme('screens.xs')})`]: {
                    marginTop: '2rem',
                },
            };
        });

        elementsRelaxed.forEach((subElement) => {
            output[`${element} + ${subElement}`] = {
                marginTop: '1rem',
                [`@media (min-width: ${theme('screens.xs')})`]: {
                    marginTop: '1.5rem',
                },
            };
        });

        elementsNormal.forEach((subElement) => {
            output[`${element} + ${subElement}`] = {
                marginTop: '1rem',
            };
        });

        elementsTight.forEach((subElement) => {
            output[`${element} + ${subElement}`] = {
                marginTop: '0.5rem',
            };
        });
    });

    addComponents({
        '.prose': {
            ...output,
        },
    });
});

module.exports = ProseSpacing;

/**
 * Generate scoped wireframe styles if provided on the wireframe component
 */
const generateWireframeStyles = function () {
    const wireframeElements = Array.from(document.querySelectorAll('[data-wireframe]'));

    wireframeElements.forEach((wireframeElement) => {
        const wireframeName = wireframeElement.getAttribute('data-wireframe');
        const wireframeCss = wireframeElement.getAttribute('data-wireframe-css');

        if (!wireframeName || !wireframeCss) return;

        const wireframeStyleTag = document.querySelector(`[data-style-for-wireframe="${wireframeName}"]`);

        if (wireframeStyleTag) return;

        const wireframeStyles = prefixCSS(wireframeCss, `[data-wireframe="${wireframeName}"]`);

        createWireframeStylesElement(wireframeName, wireframeStyles);
    });
};

function createWireframeStylesElement(name, styleString) {
    const styleElement = document.createElement('style');

    styleElement.setAttribute('data-style-for-wireframe', name);

    if (styleElement.styleSheet) {
        styleElement.styleSheet.cssText = styleString;
    } else {
        styleElement.appendChild(document.createTextNode(styleString));
    }

    document.getElementsByTagName('head')[0].appendChild(styleElement);
}

/* eslint-disable */
// Credits: https://stackoverflow.com/questions/33235262/add-prefix-to-css-rules-with-javascript
const prefixCSS = function (rules, selector) {
    var classLen = selector.length,
        char,
        nextChar,
        isAt,
        isIn;

    // makes sure the selector will not concatenate the selector
    selector += ' ';

    // removes comments
    rules = rules.replace(/\/\*(?:(?!\*\/)[\s\S])*\*\/|[\r\n\t]+/g, '');

    // makes sure nextChar will not target a space
    rules = rules.replace(/}(\s*)@/g, '}@');
    rules = rules.replace(/}(\s*)}/g, '}}');

    for (var i = 0; i < rules.length - 2; i++) {
        char = rules[i];
        nextChar = rules[i + 1];

        if (char === '@' && nextChar !== 'f') isAt = true;
        if (!isAt && char === '{') isIn = true;
        if (isIn && char === '}') isIn = false;

        if (
            !isIn &&
            nextChar !== '@' &&
            nextChar !== '}' &&
            (char === '}' || char === ',' || ((char === '{' || char === ';') && isAt))
        ) {
            rules = rules.slice(0, i + 1) + selector + rules.slice(i + 1);
            i += classLen;
            isAt = false;
        }
    }

    // prefix the first select if it is not `@media` and if it is not yet prefixed
    if (rules.indexOf(selector) !== 0 && rules.indexOf('@') !== 0) rules = selector + rules;

    const trimmedRules = rules.replace(/\s\s+/g, ' ');

    return trimmedRules;
};
/* eslint-enable */

export { generateWireframeStyles as default };

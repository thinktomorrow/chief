/**
 * Increase the deepest index by 1. Both square brackets as dotted syntax
 * e.g.
 * foobar[0][test] will be foobar[1][test]
 * foobar[0][values][0][test] will be foobar[0][values][1][test]
 * foobar.0.test will be foobar1.test
 * foobar.0.values.0.test will be foobar.0.values.1.test
 *
 * @param string
 * @param repeatKey
 * @returns {*}
 */
function increaseDeepestIndex(string, repeatKey) {
    // Search pattern - Remove last part of key (e.g. options.0.value => options.0)
    const regexLastPart = /\.([^.]*)$/;
    const originalDottedKey = repeatKey.replace(regexLastPart, '');

    // Replace pattern - Increase last number of key (e.g. options.0 => options.1)
    const regexLastNumber = /([^.]*)$/;
    const newDottedKey = originalDottedKey.replace(regexLastNumber, (match) => parseInt(match, 10) + 1);

    // Replace dotted keys like options.0.value
    const replacedString = string.replace(new RegExp(_escapeRegExp(originalDottedKey), 'g'), newDottedKey);

    // Replace square brackets keys like options[0][value]
    return replacedString.replace(
        new RegExp(_escapeRegExp(_replaceDotsWithSquareBrackets(originalDottedKey)), 'g'),
        _replaceDotsWithSquareBrackets(newDottedKey)
    );
}

function _escapeRegExp(stringToGoIntoTheRegex) {
    return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}

// E.g. foobar.0.test => foobar[0][test]
function _replaceDotsWithSquareBrackets(string) {
    return string.replace(/\.(.+?)(?=\.|$)/g, (match, value) => `[${value}]`);
}

export { increaseDeepestIndex };

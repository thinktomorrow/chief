/**
 * Escapes illegal CSS class name characters (e.g. dot)
 * @param {String} string
 * @returns string
 */
const escapeIllegalCharacters = (string) => {
    return string.replace('.', '\\.').replace('/', '\\/');
};

module.exports = { escapeIllegalCharacters };

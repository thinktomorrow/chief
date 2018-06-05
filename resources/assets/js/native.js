/**
 * List here all the js utilities needed to be
 * loaded after the Vue instantiation
 */

require('./utilities/form-submit');

/**
 * Redactor wysiwyg
 * The editor will be set on all [data-editor] instances
 */
require('./vendors/redactor/redactor.js');

//$R('[data-editor]');

/** Tippy tooltip init */
window.tippy('[title]', {
    arrow: true,
    animation: 'shift-toward'
});


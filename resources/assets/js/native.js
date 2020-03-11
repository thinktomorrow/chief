/**
 * List here all the js utilities needed to be
 * loaded after the Vue instantiation
 */

require('./utilities/form-submit');

/** Tippy tooltip init */
window.tippy('[title]', {
    arrow: true,
    animation: 'shift-toward'
});


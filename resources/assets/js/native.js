import IndexSorting from './utilities/sortable';
import FormSubmit from './utilities/form-submit';
import initConditionalFields from './utilities/conditional-fields';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
require('./utilities/navigation');
require('./utilities/character-count');

// TODO: how to load addons js without changing this file?
require('../../../src/Addons/Repeat/resources/js/init-repeat-fields');

FormSubmit.listen('[data-submit-form]');

initConditionalFields();

/**
 * Sortable
 */
if (document.getElementById('js-sortable')) {
    new IndexSorting({
        // any options go here
        isSorting: document.getElementById('js-sortable').hasAttribute('data-sort-on-load'),
        endpoint: document.getElementById('js-sortable').getAttribute('data-sort-route'),
    });
}

/**
 * Sidebar components
 */
require('./components/sidebarComponents');

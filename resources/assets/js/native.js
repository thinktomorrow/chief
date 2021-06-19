import IndexSorting from './utilities/sortable';
import FormSubmit from './utilities/form-submit';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
require('./utilities/navigation');
require('./utilities/toggle-fields');

FormSubmit.listen('[data-submit-form]');

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

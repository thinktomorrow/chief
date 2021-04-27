import IndexSorting from './utilities/sortable';
import FormSubmit from './utilities/form-submit';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
require('./utilities/navigation');

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
 * Sidebar
 */
require('./components/fragment/fragments');
require('./components/links');
require('./components/fieldcomponents');
require('./components/fragmentSelection');

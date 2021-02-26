/**
 * List here all the js utilities needed to be
 * loaded after the Vue instantiation
 */

require('./utilities/form-submit');

/** Sortable */
import { IndexSorting } from './utilities/sortable';
if (document.getElementById('js-sortable')) {
    new IndexSorting({
        // any options go here
        isSorting: document.getElementById('js-sortable').hasAttribute('data-sort-on-load'),
        endpoint: document.getElementById('js-sortable').getAttribute('data-sort-route'),
    });
}

/** Sidebar */
require('./components/fragment/fragments');
require('./sidebar/links');
require('./sidebar/fieldcomponents');

import IndexSorting from './utilities/sortable';
import FormSubmit from './utilities/form-submit';
import initConditionalFields from './utilities/conditional-fields';
import initRepeatFieldsOnPageLoad from '../../../src/Addons/Repeat/resources/js/init-repeat-fields-on-pageload';
import initCopyToClipboard from './utilities/copy-to-clipboard';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
require('./utilities/navigation');
require('./utilities/character-count');

initRepeatFieldsOnPageLoad();

FormSubmit.listen('[data-submit-form]');

initConditionalFields();
initCopyToClipboard();

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

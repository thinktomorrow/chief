import IndexSorting from './utilities/sortable';
import FormSubmit from './utilities/form-submit';
import initConditionalFields from './utilities/conditional-fields';
import initCopyToClipboard from './utilities/copy-to-clipboard';
import initCollapsibleNavigation from './utilities/collapsible-navigation';
import initDropdowns from './utilities/dropdown';
import initAnimatedToggle from './utilities/animated-toggle';
import initRepeatFields from './forms/fields/init-repeat-fields';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
initCollapsibleNavigation();
initDropdowns();
initConditionalFields();
initCopyToClipboard();
initAnimatedToggle('[data-mobile-navigation]', '[data-mobile-navigation-toggle]', {
    animationClass: 'animate-slide-in-nav lg:animate-none',
});
initRepeatFields();

require('./utilities/character-count');

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

/** Form logic - submit forms async or via sidebar */
require('./forms/index');

import initCopyToClipboard from './utilities/copy-to-clipboard';
import initCollapsibleNavigation from './utilities/collapsible-navigation';
import initDropdowns from './utilities/dropdown';
import initAnimatedToggle from './utilities/animated-toggle';
import initSortable from './sortable/sortable-init';
import initFormSubmitOnChange from './utilities/form-submit-on-change';
import registerClassToggles from './utilities/toggle-class';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
initCollapsibleNavigation();
initDropdowns();
initCopyToClipboard();
initAnimatedToggle('[data-mobile-navigation]', '[data-mobile-navigation-toggle]', {
    animationClass: 'animate-slide-in-nav lg:animate-none',
});
initFormSubmitOnChange();
registerClassToggles();

/** Sortable */
initSortable('[data-sortable]');

/** Form logic - submit forms async or via sidebar */
require('./forms/index');

/** Table logic - bulk actions */
require('./tables/index');

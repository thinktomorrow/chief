import './vendors';
import './utilities/chiefRedactorImageUpload';

import copyDirective from './alpine-directives/copy';
import dropdown from './alpine-directives/dropdown';
import tableFilters from './alpine-directives/tablefilters';
import multiselectDirective from './alpine-directives/multiselect';
import sortableDirective from './alpine-directives/sortable';
import preventSubmitOnEnter from './alpine-directives/prevent-submit-on-enter';
import initCollapsibleNavigation from './utilities/collapsible-navigation';
import initDropdowns from './utilities/dropdown';
import initAnimatedToggle from './utilities/animated-toggle';
import initSortable from './sortable/sortable-init';

import initFormSubmitOnChange from './utilities/form-submit-on-change';
import registerClassToggles from './utilities/toggle-class';
import selectlist from './alpine-directives/selectlist';
import bulkselect from './alpine-directives/bulkselect';

/**
 * List here all the js utilities needed to be loaded after the Vue instantiation
 */
initCollapsibleNavigation();
initDropdowns();
initAnimatedToggle('[data-mobile-navigation]', '[data-mobile-navigation-toggle]', {
    animationClass: 'animate-slide-in-nav lg:animate-none',
});

initFormSubmitOnChange();
initSortable('[data-sortable]');
registerClassToggles();

// Form logic - submit forms async or via sidebar
require('./forms/index');
// Table logic - bulk actions
require('./tables/index');

/**
 * --------------------------------
 * Livewire & Alpine
 * --------------------------------
 */
require('./sortable/sortable-livewire');

window.Alpine.data('dropdown', dropdown);
window.Alpine.data('selectlist', selectlist);
window.Alpine.data('bulkselect', bulkselect);
window.Alpine.data('tableFilters', tableFilters);
window.Alpine.directive('multiselect', multiselectDirective);
window.Alpine.directive('prevent-submit-on-enter', preventSubmitOnEnter);
window.Alpine.directive('sortable', sortableDirective);
window.Alpine.directive('copy', copyDirective);

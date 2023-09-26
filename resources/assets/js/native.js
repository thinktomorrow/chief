import Alpine from 'alpinejs';
import dropdownDirective from './alpine-directives/dropdown';
import multiselectDirective from './alpine-directives/multiselect';
import initCopyToClipboard from './utilities/copy-to-clipboard';
import initCollapsibleNavigation from './utilities/collapsible-navigation';
import initDropdowns from './utilities/dropdown';
import initAnimatedToggle from './utilities/animated-toggle';
import initSortable from './sortable/sortable-init';
import initFormSubmitOnChange from './utilities/form-submit-on-change';
import registerClassToggles from './utilities/toggle-class';
import preventSubmitOnEnter from './alpine-directives/prevent-submit-on-enter';

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
initSortable('[data-sortable]');
registerClassToggles();

// Form logic - submit forms async or via sidebar
require('./forms/index');
// Table logic - bulk actions
require('./tables/index');
require('./sortable/sortable-livewire');

/**
 * --------------------------------
 * Alpine
 * --------------------------------
 */
Alpine.directive('dropdown', dropdownDirective);
Alpine.directive('multiselect', multiselectDirective);
Alpine.directive('prevent-submit-on-enter', preventSubmitOnEnter);

window.Alpine = Alpine;
Alpine.start();

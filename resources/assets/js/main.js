import './forms/index';
import './sortable/index';
import './tables/index';

/* Alpine directives */
import buttonGroup from './alpine-directives/button-group';
import copyDirective from './alpine-directives/copy';
import dialog from './alpine-directives/dialog';
import dropdown from './alpine-directives/dropdown';
import multiselectDirective from './alpine-directives/multiselect';
import preventSubmitOnEnter from './alpine-directives/prevent-submit-on-enter';
import selectlist from './alpine-directives/selectlist';
import tabs from './alpine-directives/tabs';

/* Utilities */
import initAnimatedToggle from './utilities/animated-toggle';

window.addEventListener('DOMContentLoaded', () => {
    initAnimatedToggle('[data-mobile-navigation]', '[data-mobile-navigation-toggle]', {
        animationClass: 'animate-slide-in-nav lg:animate-none',
    });
});

/* Register Alpine directives */
window.Alpine.data('buttonGroup', buttonGroup);
window.Alpine.data('dialog', dialog);
window.Alpine.data('dropdown', dropdown);
window.Alpine.data('selectlist', selectlist);
window.Alpine.data('tabs', tabs);
window.Alpine.directive('multiselect', multiselectDirective);
window.Alpine.directive('prevent-submit-on-enter', preventSubmitOnEnter);
window.Alpine.directive('copy', copyDirective);

/* Tell Vite to build all files in this directory */
import.meta.glob(['../img/**', '../fonts/**']);

import './forms/index';
import './sortable/index';
import './tables/index';

/* Alpine directives */
import copyDirective from './alpine-directives/copy';
import dropdown from './alpine-directives/dropdown';
import multiselectDirective from './alpine-directives/multiselect';
import preventSubmitOnEnter from './alpine-directives/prevent-submit-on-enter';
import selectlist from './alpine-directives/selectlist';

/* Utilities */
import initAnimatedToggle from './utilities/animated-toggle';

window.addEventListener('DOMContentLoaded', () => {
    initAnimatedToggle('[data-mobile-navigation]', '[data-mobile-navigation-toggle]', {
        animationClass: 'animate-slide-in-nav lg:animate-none',
    });
});

/* Register Alpine directives */
window.Alpine.data('dropdown', dropdown);
window.Alpine.data('selectlist', selectlist);
window.Alpine.directive('multiselect', multiselectDirective);
window.Alpine.directive('prevent-submit-on-enter', preventSubmitOnEnter);
window.Alpine.directive('copy', copyDirective);

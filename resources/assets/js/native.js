import Alpine from 'alpinejs';
import initCopyToClipboard from './utilities/copy-to-clipboard';
import initCollapsibleNavigation from './utilities/collapsible-navigation';
import initDropdowns from './utilities/dropdown';
import initAnimatedToggle from './utilities/animated-toggle';
import initSortable from './sortable/sortable-init';
import initFormSubmitOnChange from './utilities/form-submit-on-change';
import vueFields from './forms/fields/vue-fields';
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
initSortable('[data-sortable]');
registerClassToggles();

// Form logic - submit forms async or via sidebar
require('./forms/index');
// Table logic - bulk actions
require('./tables/index');
require('./sortable/sortable-livewire');
// So livewire scripts play nice with vue
require('livewire-vue');

window.vueFieldsRefresh = (el) => vueFields(el);

// eslint-disable-next-line no-empty-pattern
Alpine.directive('prevent-submit-on-enter', (el, {}, { cleanup }) => {
    const handler = (e) => {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    };

    el.addEventListener('keydown', handler);

    cleanup(() => {
        el.removeEventListener('click', handler);
    });
});

window.Alpine = Alpine;
Alpine.start();

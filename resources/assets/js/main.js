import Errors from './_utilities/Errors';
import Form from './_utilities/Form';

/**
 * Shop components
 */
Vue.component('mkiha-tab', require('./_components/Tab.vue').default);
Vue.component('mkiha-tabs', require('./_components/Tabs.vue').default);
Vue.component('mkiha-translation-tabs', require('./_components/TranslationTabs.vue').default);
Vue.component('mkiha-multiselect', require('./_components/MultiSelect.vue').default);

Vue.component('mkiha-modal', require('./_components/Modal.vue').default);
Vue.component('mkiha-sidebar', require('./_components/Sidebar.vue').default);
Vue.component('mkiha-alert', require('./_components/Alert.vue').default);
Vue.component('mkiha-delete', require('./_components/RemoveButton.vue').default);
Vue.component('mkiha-error', require('./_components/Error.vue').default);

window.Errors = Errors;
window.Form = Form;

// sticky polyfill init
window.Stickyfill = require('stickyfilljs');
Stickyfill.add(document.querySelectorAll('.sticky'));
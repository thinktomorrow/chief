/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./vendors');
require('./utilities/chiefRedactorImageUpload');

import Errors from './utilities/Errors';
import Form from './utilities/Form';

window.Errors = Errors;
window.Form = Form;

/** Chief components */
Vue.component('dropdown', require('./components-vue/Dropdown.vue').default);
Vue.component('button-dropdown', require('./components-vue/ButtonDropdown.vue').default);
Vue.component('options-dropdown', require('./components-vue/OptionsDropdown.vue').default);

Vue.component('tab', require('./components-vue/Tab.vue').default);
Vue.component('tabs', require('./components-vue/Tabs.vue').default);
Vue.component('translation-tabs', require('./components-vue/TranslationTabs.vue').default);
Vue.component('chief-multiselect', require('./components-vue/MultiSelect.vue').default);

Vue.component('modal', require('./components-vue/Modal.vue').default);
Vue.component('alert', require('./components-vue/Alert.vue').default);
Vue.component('delete', require('./components-vue/RemoveButton.vue').default);
Vue.component('error', require('./components-vue/Error.vue').default);
Vue.component('mediagallery', require('./components-vue/MediaGallery.vue').default);
Vue.component('image-component', require('./components-vue/ImageComponent').default);
Vue.component('imagesupload', require('./components-vue/ImagesUpload').default);

Vue.component('url-redirect', require('./components-vue/UrlRedirect').default);
Vue.component('link-input', require('./components-vue/LinkInput').default);

Vue.component('notifications', require('./components-vue/Notifications/Notifications.vue').default);
Vue.component('notification', require('./components-vue/Notifications/Notification.vue').default);

// sticky polyfill init
Stickyfill.add(document.querySelectorAll('.sticky'));

// Promise polyfill for support of IE9 and below
import Es6Promise from 'es6-promise';

Es6Promise.polyfill();

import 'equalizeheight';

import Errors from './utilities/Errors';
import Form from './utilities/Form';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './vendors';
import './utilities/chiefRedactorImageUpload';

window.Errors = Errors;
window.Form = Form;

/** Chief components */
window.Vue.component('dropdown', require('./components-vue/Dropdown.vue').default);
window.Vue.component('options-dropdown', require('./components-vue/OptionsDropdown.vue').default);

window.Vue.component('tab', require('./components-vue/Tab.vue').default);
window.Vue.component('tabs', require('./components-vue/Tabs.vue').default);
window.Vue.component('chief-multiselect', require('./components-vue/MultiSelect.vue').default);

window.Vue.component('modal', require('./components-vue/Modal.vue').default);
window.Vue.component('mediagallery', require('./components-vue/MediaGallery.vue').default);
window.Vue.component('image-component', require('./components-vue/ImageComponent.vue').default);
window.Vue.component('imagesupload', require('./components-vue/ImagesUpload.vue').default);

window.Vue.component('url-redirect', require('./components-vue/UrlRedirect.vue').default);
window.Vue.component('link-input', require('./components-vue/LinkInput.vue').default);

window.Vue.component('notifications', require('./components-vue/Notifications/Notifications.vue').default);
window.Vue.component('notification', require('./components-vue/Notifications/Notification.vue').default);

window.Stickyfill.add(document.querySelectorAll('.sticky'));

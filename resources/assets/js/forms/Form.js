import Panels from './sidebar/Panels';
import Api from './Api';
import initSortable from '../sortable/sortable-init';
import registerClassToggles from '../utilities/toggle-class';
import SelectFragment from '../fragments/selectFragment';
import Submit from './Submit';
import EventBus from '../utilities/EventBus';
import { initConditionalFieldsInContainer } from './conditional-fields/init-conditional-fields';

const Form = function (el, sidebar) {
    this.el = el;
    this.sidebar = sidebar;
    this.triggerSelector = '[data-sidebar-trigger]';
    this.formSelector = '[data-form]';
    this.lastVisitedTabId = null;

    this.sidebarClick = (event) => this.handleSidebarClick(event);
};

Form.prototype.getTags = function () {
    return this.el.dataset.formTags ? this.el.dataset.formTags.split(',') : [];
};

Form.prototype.hasTag = function (tags) {
    return this.getTags().filter((tag) => tags.includes(tag)).length > 0;
};

// Default tag is the panelId
Form.prototype.addTag = function (tag) {
    const tags = this.getTags();

    if (!this.hasTag([tag])) {
        tags.push(tag);
        this.el.dataset.formTags = tags.join(',');
    }
};

// Triggers to open sidebar
Form.prototype.listen = function () {
    // Sidebar form
    Array.from(this.el.querySelectorAll(this.triggerSelector)).forEach((trigger) => {
        // Provide panel id as default tag.
        this.addTag(Panels.createId(trigger.getAttribute('href')));

        trigger.removeEventListener('click', this.sidebarClick);
        trigger.addEventListener('click', this.sidebarClick);
    });

    // Inline form
    Api.listenForFormSubmits(this.el, this.onFormSubmission.bind(this), () => {
        // TODO: show to user that form hasn't been saved
    });
};

Form.prototype.handleSidebarClick = function (event) {
    event.preventDefault();
    this.sidebar.show(event.currentTarget.getAttribute('href'), {
        tags: this.getTags(),
    });
};

Form.prototype.onFormSubmission = function (responseData, meta) {
    Submit.handle(responseData, this.el, this.getTags(), meta);
};

Form.prototype.refresh = function () {
    const url = this.el.dataset.formUrl;

    if (!url) {
        return;
    }

    Api.get(url, (data) => {
        const DOM = document.createElement('div');
        DOM.innerHTML = data;
        this.el.innerHTML = DOM.firstElementChild.innerHTML;

        // So Redactor can be reinitialised when the form is refreshed
        window.dispatchEvent(new CustomEvent('chief::formrefreshed', { detail: { container: this.el } }));

        // Re-init event listeners
        this.listen();

        EventBus.publish('chief-form-refreshed', {
            element: this.el,
        });

        this.refreshCallback();
    });
};

// TODO: better design pattern than this. Now we set custom logic here per type,
// but should better by outside this Window class...
Form.prototype.refreshCallback = function () {
    initSortable('[data-sortable]', this.el);
    initConditionalFieldsInContainer(this.el);
    registerClassToggles();

    if (this.getTags().includes('fragments')) {
        new SelectFragment(this.el);
    }

    // window.Livewire.rescan(this.el);

    // Trigger tab if any
    if (this.lastVisitedTabId) {
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('chieftab', { detail: this.lastVisitedTabId }));
        }, 0);
    }
};

export { Form as default };

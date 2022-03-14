import Panels from './sidebar/Panels';
import Api from './Api';
import vueFields from './fields/vue-fields';
import initSortableGroup from '../utilities/sortable-group';
import SelectFragment from '../fragments/selectFragment';
import Submit from './Submit';
import EventBus from '../utilities/EventBus';

const Form = function (el, sidebar) {
    this.el = el;
    this.mainContainer = document.getElementById('content');
    this.sidebar = sidebar;
    this.triggerSelector = '[data-sidebar-trigger]';
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
    console.log('listen');
    // Sidebar form
    this.el.querySelectorAll(this.triggerSelector).forEach((trigger) => {
        // Provide panel id as default tag.
        this.addTag(Panels.createId(trigger.getAttribute('href')));

        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            this.sidebar.show(event.currentTarget.getAttribute('href'), {
                tags: this.getTags(),
            });
        });
    });

    // Inline form
    Api.listenForFormSubmits(this.el, this.onFormSubmission.bind(this), (error) => {
        console.error(`${error}`);
    });
};

Form.prototype.onFormSubmission = function (responseData) {
    Submit.handle(responseData, this.el, this.mainContainer, this.getTags());
};

Form.prototype.refresh = function () {
    const url = this.el.dataset.formUrl;

    if (!url) {
        console.log(this, this.el.dataset);
        console.error('no refresh url defined on this form.');
        return;
    }
    console.log('refreshing: ', url);
    Api.get(url, (data) => {
        const DOM = document.createElement('div');
        DOM.innerHTML = data;

        this.el.innerHTML = DOM.firstElementChild.innerHTML;

        // Mount Vue on our vue specific fields. Make sure that Vue mount occurs
        // before vanilla event listeners so native js can do its thing
        vueFields(this.el);

        // TODO: this method is now coming from project code (project skeleton),
        // we should however provide this from chief
        loadRedactorInstances(document);

        // Re-init event listeners
        this.listen();

        EventBus.publish('form-refreshed', {
            element: this.el,
        });

        this.refreshCallback();
    });
};

// TODO: better design pattern than this. Now we set custom logic here per type,
// but should better by outside this Window class...
Form.prototype.refreshCallback = function () {
    console.log(this.getTags());
    if (this.getTags().includes('fragments')) {
        initSortableGroup('[data-sortable-fragments]', this.el);
        new SelectFragment(this.el);
    }

    // Specific callbacks...
};

export { Form as default };

import EventBus from '../utilities/EventBus';
import Form from './Form';

const Forms = function (sidebar) {
    this.sidebar = sidebar;
    this.selector = '[data-form]';

    EventBus.subscribe('chief-form-submitted', (e) => {
        // TODO: not always is currentElement a panel...
        this.refreshIn(e.targetElement, e.tags);
    });

    EventBus.subscribe('sidebarPanelActivated', (e) => {
        this.load(e.panel.el);

        // TODO: also clear events subscriptions when panel closed...
        // EventBus.subscribe('chief-form-submitted', (e) => {
        //         this.refreshIn(e.container, e.panel.getTags());
        //     });
    });
};

Forms.prototype.load = function (container = document) {
    this.listenIn(container);

    container.querySelectorAll(this.selector).forEach((el) => {
        new Form(el, this.sidebar).refreshCallback();
    });
};

Forms.prototype.listenIn = function (container = document) {
    container.querySelectorAll(this.selector).forEach((el) => {
        new Form(el, this.sidebar).listen();
    });
};

Forms.prototype.refreshIn = function (container = document, tags) {
    console.log('windows refresh in ', container, 'for tags: ', tags);
    container.querySelectorAll(this.selector).forEach((el) => {
        const form = new Form(el, this.sidebar);

        if (form.hasTag(tags)) {
            form.refresh();
        }
    });
};

export { Forms as default };

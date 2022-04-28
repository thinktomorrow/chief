import EventBus from '../utilities/EventBus';
import Form from './Form';

const Forms = function (mainContainer, sidebar) {
    this.sidebar = sidebar;
    this.mainContainer = mainContainer;
    this.selector = '[data-form]';

    EventBus.subscribe('chief-form-submitted', (e) => {
        const inSidebar = this.sidebar.sidebarContainer.el.contains(e.currentElement);
        const targetElement =
            inSidebar && this.sidebar.findPanelTarget() ? this.sidebar.findPanelTarget().el : this.mainContainer;

        if (e.response.redirect_to) {
            window.location.href = e.response.redirect_to;
            return;
        }

        // If our submit happens from the sidebar, we'll trigger the
        if (inSidebar) {
            this.handleSubmitFromSidebar(e.response, e.meta);
        }

        this.refreshIn(targetElement, e.tags);
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
    Array.from(container.querySelectorAll(this.selector)).forEach((el) => {
        const form = new Form(el, this.sidebar);

        if (form.hasTag(tags)) {
            form.refresh();
        }
    });
};

Forms.prototype.handleSubmitFromSidebar = function (responseData, meta) {
    // e.g. existing fragments...
    if (meta && meta.method === 'get') {
        this.sidebar.refresh(responseData);
        return true;
    }

    if (responseData.redirect_to) {
        this.sidebar.show(responseData.redirect_to);
        return false;
    }

    this.sidebar.backAfterSubmit();
    return true;
};

export { Forms as default };

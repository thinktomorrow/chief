import EventBus from '../utilities/EventBus';
import Form from './Form';

const Forms = function (mainContainer, sidebar) {
    this.sidebar = sidebar;
    this.mainContainer = mainContainer;
    this.selector = '[data-form]';
    this.lastVisitedTabId = null;

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

        this.refreshIn(e.tags, targetElement);
    });

    EventBus.subscribe('sidebarPanelActivated', (e) => {
        this.load(e.panel.el);

        // TODO: also clear events subscriptions when panel closed...
        // EventBus.subscribe('chief-form-submitted', (e) => {
        //         this.refreshIn(e.container, e.panel.getTags());
        //     });
    });

    // Listen for tab changes (so we can direct the visitor to the same tab after form refresh
    window.addEventListener('chieftab', this.onTabDispatch.bind(this));
    window.addEventListener('chief-refresh-form', this.onChiefRefreshForm.bind(this));
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

Forms.prototype.refreshIn = function (tags, container = document) {
    Array.from(container.querySelectorAll(this.selector)).forEach((el) => {
        const form = new Form(el, this.sidebar);

        if (form.hasTag(tags)) {
            form.lastVisitedTabId = this.lastVisitedTabId;
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

    if (responseData.sidebar_redirect_to) {
        this.sidebar.show(responseData.sidebar_redirect_to);
        return false;
    }

    this.sidebar.backAfterSubmit();
    return true;
};

Forms.prototype.onTabDispatch = function (e) {
    this.lastVisitedTabId = e.detail;
};

Forms.prototype.onChiefRefreshForm = function (e) {
    const { selector, refreshUrl } = e.detail;

    Array.from(document.querySelectorAll(selector)).forEach((el) => {
        new Form(el, this.sidebar).refresh(refreshUrl);
    });
};

export { Forms as default };

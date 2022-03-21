import Api from '../Api';
import Panel from './Panel';
import Panels from './Panels';
import EventBus from '../../utilities/EventBus';
import Container from './Container';
import vueFields from '../fields/vue-fields';
import Submit from '../Submit';

export default class {
    constructor(options = {}) {
        this.debug = options.debug || false;

        this.mainContainer = document.getElementById('content');
        this.panels = new Panels();
        this.sidebarContainer = new Container(document.getElementById('js-sidebar-container'));

        /**
         * Register unique trigger handler
         *
         * if we'd call the method directly as callback, it cannot be
         * removed as it is regarded to be a different function.
         */
        this.handleCloseReference = () => this.back();
        this.listenForEscapeKey();
    }

    currentContainer() {
        return this.panels.findActive() ? this.panels.findActive().el : document;
    }

    listenForCloseEvents() {
        this.sidebarContainer.getCloseTriggers().forEach((trigger) => {
            trigger.removeEventListener('click', this.handleCloseReference);
            trigger.addEventListener('click', this.handleCloseReference);
        });
    }

    /**
     * Show an existing panel or create a new one if it doesn't exist. In the case of the latter,
     * the html content is fetched from the server first. This panel is the 'active' panel.
     * If a panel already exists, a refresh is avoided and the existing panel is shown.
     *
     * @param url
     * @param options
     */
    show(url, options = {}) {
        const id = Panels.createId(url);

        if (this.panels.find(id)) {
            this.showPanel(id);
            return;
        }

        this.fetchUrl(url, options);
    }

    showPanel(id) {
        if (!this.sidebarContainer.isOpen()) {
            this.sidebarContainer.open();
        }

        // Hide current active panel
        if (this.panels.findActive()) {
            this.panels.findActive().hide();
        }

        // Set new panel as active and show it
        this.panels.markAsActive(id);
        this.panels.findActive().show();
    }

    fetchUrl(url, options = {}) {
        const panelId = Panels.createId(url);

        // Add a new panel element to dom
        const panelEl = document.createElement('div');
        panelEl.setAttribute('data-panel-id', panelId);

        this.sidebarContainer.dom().appendChild(panelEl);

        // Fetch the html content from the given url and insert it in the panel element
        Api.get(url, (data) => {
            panelEl.innerHTML = data;

            // only mount Vue on our vue specific fields and not on the form element itself so
            // that the submit event still works. I know this is kinda hacky. Make sure that
            // vue mount occurs before a sidebar activation so native js can do its thing
            vueFields(panelEl);

            // TODO: refactor to trigger submit.js for panel submissions...
            // This is somewhat in conflict with the Form.listen() logic.
            // We should make sure that both don't conflict with each other.
            Api.listenForFormSubmits(panelEl, this.onFormSubmission.bind(this), (error) => {
                console.error(`${error}`);
            });

            this.panels.add(
                new Panel(panelId, url, this.panels.findActive() ? this.panels.findActive() : null, panelEl, options)
            );

            this.showPanel(panelId);
            this.refresh();

            // if (afterShowCallback) {
            //     afterShowCallback(panelId);
            // }

            // Creating a custom event so native js like redactor js can be initiated async
            // needs to dispatch after vue instances get created otherwise they override
            // all redactor event listeners like toolbar clicks ...
            window.dispatchEvent(new CustomEvent('chief::newpanel', { detail: { panel: this.panels.findActive() } }));

            EventBus.publish('sidebarPanelCreated', { panel: this.panels.findActive() });
        });
    }

    onFormSubmission(responseData, metadata) {
        const activePanel = this.panels.findActive();
        const targetPanel = this.panels.findFirstSubmitTarget(activePanel.parent);

        Submit.handle(
            responseData,
            activePanel.el,
            targetPanel ? targetPanel.el : document,
            activePanel.getTags(),
            () => {
                // GET request stays on same page and reloads it with the given response.
                if (metadata.method === 'get') {
                    this.refresh(responseData);
                    return;
                }

                this.backAfterSubmit();
            }
        );
    }

    backAfterSubmit() {
        const targetPanel = this.panels.findFirstSubmitTarget(this.panels.findActive().parent);

        if (!targetPanel) {
            this.close();
            return;
        }

        this.backTo(targetPanel.id);
    }

    /**
     * Handle the closing of the current panel and determine the next one.
     * Either the user clicks the close button or has saved the panel form.
     */
    back() {
        if (!this.panels.findActive() || !this.panels.findActive().parent) {
            this.close();
            return;
        }

        this.backTo(this.panels.findActive().parent.id, true);
    }

    backTo(panelId, keepCurrentPanel = false) {
        const currentPanel = this.panels.findActive();

        this.showPanel(panelId);

        if (!keepCurrentPanel) {
            this.panels.remove(currentPanel.id);
        }
    }

    close() {
        // At top level so close entire sidebar which also clears out the panels
        // TODO: Check for unsaved content before clicking submit...
        this.sidebarContainer.close();

        // A small delay so the sidebar isn't empty when closing
        setTimeout(() => {
            this.panels.clear();
        }, 400);
    }

    refresh(data = null) {
        if (this.panels.findActive()) {
            this.sidebarContainer.renderCloseButton();
        }

        this.replacePanelComponents(() => {
            this.listenForCloseEvents();

            if (this.panels.findActive()) {
                EventBus.publish('sidebarPanelActivated', { panel: this.panels.findActive() });
            }
        }, data);
    }

    listenForEscapeKey() {
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.panels.findActive()) {
                this.back();
            }
        });
    }

    /**
     * Replace components found within the active panel with their up to date rendered html
     * coming from serverside. Each component is marked by the [data-sidebar-component]
     * attribute. A unique value is required to distinguish the different components.
     */
    replacePanelComponents(callback, data) {
        if (!this.panels.findActive()) {
            callback();
            return;
        }

        // TODO: replace by data-form (use Window objects?)
        const replaceableElements = this.panels.findActive().el.querySelectorAll('[data-sidebar-component]');

        if (replaceableElements.length < 1) {
            callback();
            return;
        }

        // If data is already fetched - which is the case for get requests - there is no need
        // to refetch it for the dom update. For POST requests this is still required.
        if (data) {
            this.replacePanelDom(data);

            callback();
        } else {
            Api.get(this.panels.findActive().url, (_data) => {
                this.replacePanelDom(_data);

                callback();
            });
        }
    }

    replacePanelDom(data) {
        const replaceableElements = this.panels.findActive().el.querySelectorAll('[data-sidebar-component]');

        if (replaceableElements.length < 1) return;

        const DOM = document.createElement('div');

        DOM.innerHTML = data;

        replaceableElements.forEach((el) => {
            const componentKey = el.getAttribute('data-sidebar-component');

            this.panels.activePanel.replaceDom(
                `[data-sidebar-component="${componentKey}"]`,
                DOM.querySelector(`[data-sidebar-component="${componentKey}"]`).innerHTML
            );
        });
    }
}

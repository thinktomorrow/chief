import Api from './Api';
import Panel from './Panel';
import Panels from './Panels';
import EventBus from '../../utilities/EventBus';

export default class {
    constructor(triggerSelector, container, options) {
        this.triggerSelector = triggerSelector;
        this.container = container;
        this.panels = new Panels();
        this.newPanelCreated = options.newPanelCreated || null;
        this.panelFormSubmitted = options.panelFormSubmitted || null;
        this.onFail = options.onFail || null;
        this.events = options.events || {};

        this.init();
    }

    init() {
        // Register unique trigger handler
        this.handle = (event) => this._handlePanelTrigger(event);

        this.scanForPanelTriggers();

        // Listen for close triggers on the sidebar container
        this.container.closeTriggers.forEach((trigger) => {
            trigger.addEventListener('click', this.backOrClose.bind(this));
        });

        // Subscribe events via our EventBus
        Object.keys(this.events).forEach((key) => {
            EventBus.subscribe(key, this.events[key]);
        });
    }

    scanForPanelTriggers() {
        Array.from(document.querySelectorAll(this.triggerSelector)).forEach((el) => {
            el.removeEventListener('click', this.handle);
            el.addEventListener('click', this.handle);
        });
    }

    _handlePanelTrigger(event) {
        event.preventDefault();

        const link = event.target.hasAttribute('href') ? event.target : event.target.closest('[href]');

        if (!link) return;

        this.show(link.getAttribute('href'));
    }

    show(url) {
        const id = Panels.createId(url);

        // if present in panels, than show the existing panel.
        if (this.panels.find(id)) {
            this._activate(id);
            return;
        }

        this._activateNewPanel(id, url);
    }

    _activateNewPanel(id, url) {
        // Add new panel container to dom
        const newPanelContainer = document.createElement('div');
        newPanelContainer.setAttribute('data-panel-id', id);

        this.container.dom().appendChild(newPanelContainer);

        Api.get(url, newPanelContainer, (data) => {
            newPanelContainer.innerHTML = data;

            // only mount Vue on our vue specific fields and not on the form element itself
            // so that the submit event still works. I know this is kinda hacky.
            Array.from(newPanelContainer.querySelectorAll('[data-vue-fields]')).forEach((el) => {
                new window.Vue({ el }); // eslint-disable-line
            });

            // creating a custom event so native js like redactor js can be initiated async
            // needs to dispatch after vue instances get created otherwise they overrides
            // all redactor event listeners like toolbar clicks ...
            const newPanelEvent = new Event('chief::newpanel');
            window.dispatchEvent(newPanelEvent);

            Api.listenForFormSubmits(
                newPanelContainer,
                (response) => {
                    console.log('success', response);
                    this.backOrClose(false);

                    if (this.panelFormSubmitted) {
                        this.panelFormSubmitted();
                    }
                },
                (error) => {
                    console.error(`showing error: ${error}`);
                }
            );

            if (!this.container.isOpen()) {
                this.container.open();
            }

            this.panels.add(
                new Panel(id, url, this.panels.findActive() ? this.panels.findActive() : null, newPanelContainer)
            );
            this._activate(id);

            if (this.newPanelCreated) {
                this.newPanelCreated(this.panels.find(id));
            }
        });
    }

    _activate(id) {
        // Hide current active panel
        if (this.panels.findActive()) {
            this.panels.findActive().hide();
        }

        // Set new panel as active and show it
        this.panels.markAsActive(id);
        this.panels.findActive().show();

        // TODO: pass here type to switch templates x/terug/...
        this.container.renderCloseButton();
        this.scanForPanelTriggers();
    }

    /**
     * Handle the closing of the current panel and determine the next one.
     * Either the user clicks the close button or has saved the panel form.
     */
    backOrClose(keepPreviousPanel = true) {
        // Back to parent
        if (this.panels.findActive().parent) {
            const previousId = this.panels.findActive().id;

            this.show(this.panels.findActive().parent.url);
            this.replacePanelComponents();

            if (!keepPreviousPanel) {
                this.panels.remove(previousId);
            }

            return;
        }

        // At top level so close entire sidebar which also clears out the panels
        // TODO: Check for unsaved content before clicking submit...
        this.panels.clear();
        this.container.close();
    }

    /**
     * Replace components found within the active panel with their up to date rendered html
     * coming from serverside. Each component is marked by the [data-sidebar-component]
     * attribute. A unique value is required to distinguish the different components.
     */
    replacePanelComponents() {
        if (!this.panels.findActive()) return;

        Array.from(this.panels.findActive().el.querySelectorAll('[data-sidebar-component]')).forEach((el) => {
            const componentKey = el.getAttribute('data-sidebar-component');

            Api.get(this.panels.findActive().url, el, (data) => {
                const DOM = document.createElement('div');

                DOM.innerHTML = data;

                this.panels.activePanel.replaceComponent(
                    `[data-sidebar-component="${componentKey}"]`,
                    DOM.querySelector(`[data-sidebar-component="${componentKey}"]`).innerHTML
                );

                this.scanForPanelTriggers();
            });
        });
    }
}

import Api from './Api';
import Panel from './Panel';
import Panels from './Panels';
import EventBus from '../../utilities/EventBus';
import Components from './Components';
import Container from './Container';

export default class {
    constructor(options = {}) {
        this.debug = options.debug || false;
        this.components = new Components(options.components || []);
        this.panels = new Panels();

        this.triggerSelector = options.triggerSelector || '[data-sidebar-trigger]';
        this.componentKeyAttribute = options.componentKeyAttribute || 'data-sidebar-trigger';

        this.mainContainer = options.mainContainer || document;
        this.sidebarContainer = new Container(
            options.sidebarContainer || document.getElementById('js-sidebar-container')
        );

        if (!this.mainContainer) {
            throw new Error('No main container element found in DOM.');
        }

        /**
         * Register unique trigger handler
         *
         * if we'd call the method directly as callback, it cannot be
         * removed as it is regarded to be a different function.
         */
        this.handleTriggerReference = (event) => this._handleTrigger(event);
        this.handleCloseReference = () => this.backOrClose();

        this.listenForEvents();
        this.listenForLivewireEvents();
        this.listenForEscapeKey();

        // Subscribe events via the global EventBus
        if (options.events) {
            Object.keys(options.events).forEach((key) => {
                EventBus.subscribe(key, options.events[key]);
            });
        }

        if (options.reloadEvents) {
            options.reloadEvents.forEach((eventKey) => {
                EventBus.subscribe(eventKey, this.listenForEvents.bind(this));
            });
        }
    }

    listenForEvents() {
        this.listenForEventsIn(this.currentContainer());
    }

    listenForEventsIn(el) {
        el.querySelectorAll(this.triggerSelector).forEach((triggerEl) => {
            triggerEl.removeEventListener('click', this.handleTriggerReference);
            triggerEl.addEventListener('click', this.handleTriggerReference);
        });

        this.sidebarContainer.getCloseTriggers().forEach((trigger) => {
            trigger.removeEventListener('click', this.handleCloseReference);
            trigger.addEventListener('click', this.handleCloseReference);
        });
    }

    currentContainer() {
        return this.panels.findActive() ? this.panels.findActive().el : document;
    }

    listenForLivewireEvents() {
        this.components.allIn(this.currentContainer()).forEach((component) => {
            if (!component.livewire) return;

            // Event when livewire is reloaded on server
            window.Livewire.on(component.livewireEventKey, () => {
                console.log('livewire reloaded ' + component.key);
                this.listenForEvents();
                component.onComponentReload();
            });

            const componentEl = component.el(this.currentContainer());
            const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

            EventBus.subscribe('sidebarFormSubmitted', (panelData) => {
                if (panelData.component.key === component.key) {
                    console.log('livewire reloading ' + component.key);
                    livewireComponent.reload();
                }
            });
        });
    }

    _handleTrigger(event) {
        event.preventDefault();

        const trigger = event.currentTarget,
            link = trigger.getAttribute('href'),
            componentKey = trigger.getAttribute(this.componentKeyAttribute);

        if (!link) {
            if (this.debug) console.error('Not showing a new panel because the trigger has no href value provided.');
            return;
        }

        this.show(link, {
            el: trigger,
            key: componentKey,
            component: this.components.find(componentKey),
            closeOnPanelFormSubmit: this.components.find(componentKey).closeOnPanelFormSubmit,
        });
    }

    /**
     * Show an existing panel or create a new one if it doesn't exist. In the case of the latter,
     * the html content is fetched from the server first.
     * This panel is the 'active' panel.
     *
     * @param url
     * @param triggerData
     */
    show(url, triggerData) {
        const id = Panels.createId(url);

        // if present in panels, than show the existing panel.
        if (this.panels.find(id)) {
            this._activate(id);
            return;
        }

        this._activateNewPanel(id, url, triggerData);
    }

    _activateNewPanel(id, url, triggerData) {
        /** Add a new panel element to dom */
        const newPanelElement = document.createElement('div');
        newPanelElement.setAttribute('data-panel-id', id);
        this.sidebarContainer.dom().appendChild(newPanelElement);

        /** Fetch the html content from the given url and insert it in the panel element */
        Api.get(url, newPanelElement, (data) => {
            newPanelElement.innerHTML = data;

            // only mount Vue on our vue specific fields and not on the form element itself
            // so that the submit event still works. I know this is kinda hacky.
            Array.from(newPanelElement.querySelectorAll('[data-vue-fields]')).forEach((el) => {
                new window.Vue({ el }); // eslint-disable-line
            });

            // creating a custom event so native js like redactor js can be initiated async
            // needs to dispatch after vue instances get created otherwise they overrides
            // all redactor event listeners like toolbar clicks ...
            const newPanelEvent = new Event('chief::newpanel');
            window.dispatchEvent(newPanelEvent);

            Api.listenForFormSubmits(
                newPanelElement,
                (response) => {
                    EventBus.publish('sidebarFormSubmitted', this.panels.findActive().eventPayload());

                    // We remove the parent if this parent should not be displayed after form submit of the child panel.
                    if (
                        this.panels.findActive().parent &&
                        this.panels.findActive().parent.triggerData.component.closeOnPanelFormSubmit
                    ) {
                        if (this.panels.findActive().parent.parent) {
                            this.panels.findActive().parent = this.panels.findActive().parent.parent;
                        } else {
                            this.panels.findActive().parent = null;
                        }
                    }

                    this.backOrClose(false);
                },
                (error) => {
                    if (this.debug) console.error(`error on form submit: ${error}`);
                }
            );

            if (!this.sidebarContainer.isOpen()) {
                this.sidebarContainer.open();
            }

            this.panels.add(
                new Panel(
                    id,
                    url,
                    this.panels.findActive() ? this.panels.findActive() : null,
                    newPanelElement,
                    triggerData
                )
            );
            this._activate(id);

            EventBus.publish('sidebarPanelCreated', this.panels.findActive().eventPayload());
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

        EventBus.publish('sidebarPanelActivated', this.panels.findActive().eventPayload());

        // TODO: pass here type to switch templates x/terug/...
        this.sidebarContainer.renderCloseButton();
        this.listenForEvents();
        this._setActiveComponents();
    }

    _setActiveComponents() {
        this.components.resetAllActive();

        const container = this.panels.findActive() ? this.panels.findActive().el : document;

        this.components.all().forEach((component) => {
            if (component.el(container)) {
                this.components.addAsActive(component.key);
            }
        });
    }

    /**
     * Handle the closing of the current panel and determine the next one.
     * Either the user clicks the close button or has saved the panel form.
     */
    backOrClose(keepPreviousPanel = true) {
        console.log(this.panels.findActive());
        // Back to parent
        if (this.panels.findActive() && this.panels.findActive().parent) {
            const previousPanel = this.panels.findActive();

            this.show(this.panels.findActive().parent.url);
            this.replacePanelComponents();

            if (!keepPreviousPanel) {
                this.panels.remove(previousPanel.id);
            }

            return;
        }

        // At top level so close entire sidebar which also clears out the panels
        // TODO: Check for unsaved content before clicking submit...
        this.panels.clear();
        this.sidebarContainer.close();
        this._setActiveComponents();
    }

    listenForEscapeKey() {
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.panels.findActive()) {
                this.backOrClose();
            }
        });
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

                this.panels.activePanel.replaceDom(
                    `[data-sidebar-component="${componentKey}"]`,
                    DOM.querySelector(`[data-sidebar-component="${componentKey}"]`).innerHTML
                );

                this.listenForEvents();
            });
        });
    }
}

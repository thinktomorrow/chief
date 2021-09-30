import Api from './Api';
import Panel from './Panel';
import Panels from './Panels';
import EventBus from '../../utilities/EventBus';
import Components from './Components';
import Container from './Container';
import vueFields from '../../fields/vue-fields';

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
        this.listenForEscapeKey();

        this.reloadLivewireEvents = options.reloadLivewireEvents || [];
        this.listenForLivewireEvents();

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
                component.onComponentReloading();
                this.listenForEvents();
                component.onComponentReloaded();
            });

            const componentEl = component.el(this.currentContainer());
            const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

            /**
             * Reload all main components when one of these events occur. The components
             * are only reloaded when the focus is on the main page. On the nested
             * fragment element this livewire reload will not be triggered.
             */
            ['sidebarFormSubmitted', ...this.reloadLivewireEvents].forEach((eventKey) => {
                EventBus.subscribe(eventKey, (evt) => {
                    // If the response has a flag that explicitly requests no livewire, we will not reload livewire
                    if (undefined !== evt.responseData.livewire && evt.responseData.livewire === false) {
                        return;
                    }

                    // Only refresh own component except for addFragment component which targets the fragments as well.
                    if (
                        evt.componentKey === component.key ||
                        (evt.componentKey === 'addFragment' && component.key === 'fragments')
                    ) {
                        livewireComponent.reload();
                    }
                });
            });
        });
    }

    _handleTrigger(event) {
        event.preventDefault();

        const trigger = event.currentTarget;
        const link = trigger.getAttribute('href');
        const componentKey = trigger.getAttribute(this.componentKeyAttribute);

        // Avoid double click - Check if already has been clicked
        if (trigger.classList.contains('is-clicked')) {
            return;
        }

        trigger.classList.add('is-clicked', 'animate-pulse');

        if (!link) {
            if (this.debug) console.error('Not showing a new panel because the trigger has no href value provided.');
            return;
        }

        // If a component key is not found the Sidebar will ignore this specific trigger handle.
        if (!this.components.find(componentKey)) {
            if (this.debug) console.error(`There is no component registered by key [${componentKey}].`);
            return;
        }

        this.show(
            link,
            {
                el: trigger,
                key: componentKey,
                component: this.components.find(componentKey),
                closeOnPanelFormSubmit: this.components.find(componentKey).closeOnPanelFormSubmit,
            },
            () => {
                setTimeout(() => {
                    trigger.classList.remove('is-clicked', 'animate-pulse');
                }, 600);
            }
        );
    }

    /**
     * Show an existing panel or create a new one if it doesn't exist. In the case of the latter,
     * the html content is fetched from the server first.
     * This panel is the 'active' panel.
     *
     * @param url
     * @param triggerData
     */
    show(url, triggerData, onActivatedCallback) {
        const id = Panels.createId(url);

        // If present in panels, than show the existing panel.
        if (this.panels.find(id)) {
            this._activate(id);

            return;
        }

        this._activateNewPanel(id, url, triggerData, onActivatedCallback);
    }

    _activateNewPanel(id, url, triggerData, onActivatedCallback) {
        // Add a new panel element to dom
        const newPanelElement = document.createElement('div');

        newPanelElement.setAttribute('data-panel-id', id);

        this.sidebarContainer.dom().appendChild(newPanelElement);

        // Fetch the html content from the given url and insert it in the panel element
        Api.get(url, (data) => {
            newPanelElement.innerHTML = data;

            // only mount Vue on our vue specific fields and not on the form element itself so
            // that the submit event still works. I know this is kinda hacky. Make sure that
            // vue mount occurs before a sidebar activation so native js can do its thing
            vueFields(newPanelElement);

            Api.listenForFormSubmits(
                newPanelElement,
                (responseData, metadata) => {
                    // Reset any error
                    newPanelElement.querySelectorAll('[data-error-placeholder]').forEach((errorElement) => {
                        errorElement.classList.add('hidden');
                    });

                    if (responseData.errors) {
                        Object.keys(responseData.errors).forEach((name) => {
                            const errorElement = newPanelElement.querySelector(`[data-error-placeholder="${name}"]`);

                            if (!errorElement) return;

                            errorElement.classList.remove('hidden');
                            errorElement.querySelector('[data-error-placeholder-content]').innerHTML =
                                responseData.errors[name];
                        });

                        // Show flicker of red?
                        // Show notification?
                        // Remove error on input?
                        return;
                    }

                    // GET request stays on same page and reloads it with the given response.
                    if (metadata && metadata.method === 'get') {
                        this.reset(responseData);
                        return;
                    }

                    EventBus.publish('sidebarFormSubmitted', {
                        ...this.panels.findActive().eventPayload(),
                        responseData,
                    });

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

            if (onActivatedCallback) {
                onActivatedCallback(id);
            }

            // Creating a custom event so native js like redactor js can be initiated async
            // needs to dispatch after vue instances get created otherwise they override
            // all redactor event listeners like toolbar clicks ...
            window.dispatchEvent(
                new CustomEvent('chief::newpanel', { detail: this.panels.findActive().eventPayload() })
            );

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

        this.reset();
    }

    reset(data = null) {
        if (this.panels.findActive()) {
            this.sidebarContainer.renderCloseButton();
        }

        this.replacePanelComponents(() => {
            this.listenForEvents();
            this._setActiveComponents();

            if (this.panels.findActive()) {
                EventBus.publish('sidebarPanelActivated', this.panels.findActive().eventPayload());
            }
        }, data);
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
        // Back to parent
        if (this.panels.findActive() && this.panels.findActive().parent) {
            const previousPanel = this.panels.findActive();

            this.show(this.panels.findActive().parent.url);

            if (!keepPreviousPanel) {
                this.panels.remove(previousPanel.id);
            }

            return;
        }

        // At top level so close entire sidebar which also clears out the panels
        // TODO: Check for unsaved content before clicking submit...
        this.panels.clear();
        this.sidebarContainer.close();

        this.reset();
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
    replacePanelComponents(callback, data) {
        if (!this.panels.findActive()) {
            callback();
            return;
        }

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

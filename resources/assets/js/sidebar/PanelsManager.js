import {Api} from "./Api"
import Panel from "./Panel"
import Panels from "./Panels"

export default class PanelsManager {
    constructor(container, newPanelCallback, submitCallback) {
        this.container = container;
        this.panels = new Panels();
        this.newPanelCallback = newPanelCallback;
        this.submitCallback = submitCallback;

        // Register unique trigger handler
        this.handle = (event) => this._handlePanelTrigger(event);
    }

    init() {
        this.scanForPanelTriggers();

        // Listen for close triggers on the sidebar container
        this.container.closeTriggers.forEach(trigger => {
            trigger.addEventListener('click', this.backOrClose.bind(this));
        });
    }

    scanForPanelTriggers() {
        Array.from(document.querySelectorAll('[data-sidebar-show]')).forEach((el) => {
            el.removeEventListener('click', this.handle)
            el.addEventListener('click', this.handle);
        });
    }

    _handlePanelTrigger(event) {
        event.preventDefault();

        const link = (event.target.hasAttribute('href'))
            ? event.target
            : event.target.closest('[href]')

        if (!link) return;

        this.show(link.getAttribute('href'));
    }

    show(url) {
        const id = this.panels.createId(url);

        // if present in panels, than show the existing panel.
        if(this.panels.find(id)) {
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
            new Vue({el: newPanelContainer.querySelector('[data-vue-fields]')});

            Api.listenForFormSubmits(newPanelContainer, () => {

                this.backOrClose(false);

                if(this.submitCallback) {
                    this.submitCallback();
                }
            });

            if(!this.container.isOpen()) {
                this.container.open();
            }

            this.panels.add(new Panel(id, url, this.panels.findActive() ? this.panels.findActive() : null, newPanelContainer));
            this._activate(id);
        })
    }

    _activate(id) {

        // Hide current active panel
        if(this.panels.findActive()) {
            this.panels.findActive().hide();
        }

        // Set new panel as active and show it
        this.panels.markAsActive(id);
        this.panels.findActive().show();

        // TODO: pass here type to switch templates x/terug/...
        this.container.renderCloseButton();
        this.scanForPanelTriggers();

        if(this.newPanelCallback) {
            this.newPanelCallback();
        }
    }

    /**
     * Handle the closing of the current panel and determine the next one.
     * Either the user clicks the close button or has saved the panel form.
     */
    backOrClose(keepPreviousPanel = true) {

        // Back to parent
        if(this.panels.findActive().parent) {

            const previousId = this.panels.findActive().id;

            this.show(this.panels.findActive().parent.url);
            this._replacePanelComponents();

            if(!keepPreviousPanel) {
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
     * Replace components found within the active panel with their updated server html.
     * A component is marked by the [data-sidebar-component] attribute. A unique
     * value is required so that the different components can be distinguished.
     *
     * @private
     */
    _replacePanelComponents() {
        Array.from(this.panels.findActive().el.querySelectorAll('[data-sidebar-component]')).forEach((el) => {
            const componentKey = el.getAttribute('data-sidebar-component');
            Api.get(this.panels.findActive().url, el, (data) => {
                let DOM = document.createElement('div');
                DOM.innerHTML = data;

                this.panels.activePanel.replaceComponent('[data-sidebar-component="' + componentKey + '"]' , DOM.querySelector('[data-sidebar-component="' + componentKey + '"]').innerHTML);
                this.scanForPanelTriggers();
            })
        });
    }

}

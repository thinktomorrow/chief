import {Api} from "./Api"
import Panel from "./Panel"
import Panels from "./Panels"

export default class PanelsManager {
    constructor(sidebar, newPanelCallback, submitCallback) {
        this.sidebar = sidebar;
        this.newPanelCallback = newPanelCallback;
        this.submitCallback = submitCallback;

        this.panels = new Panels();

        // Register unique trigger handler
        this.handle = (event) => this._handlePanelTrigger(event);
    }

    init() {
        this.listenForPanelTriggers();

        // Listen for close triggers on the sidebar container
        this.sidebar.backTriggers.forEach(trigger => {
            trigger.addEventListener('click', this.backOrClose.bind(this));
        });
    }

    listenForPanelTriggers() {
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
        const id = encodeURIComponent(url);

        // if present in panels, than show the existing panel.
        if(this.panels.find(id)) {
            this._activate(id);
            return;
        }

        this._addAndShowNewPanel(id, url);
    }

    _addAndShowNewPanel(id, url) {

        // Add new panel container to dom
        const newPanelContainer = document.createElement('div');
        newPanelContainer.setAttribute('data-panel-id', id);
        this.sidebar.dom().appendChild(newPanelContainer);

        Api.get(url, newPanelContainer, (data) => {

            newPanelContainer.innerHTML = data;

            // only mount Vue on our vue specific fields and not on the form element itself
            // so that the submit event still works. I know this is kinda hacky.
            new Vue({el: newPanelContainer.querySelector('[data-vue-fields]')});

            Api.listenForFormSubmits(newPanelContainer, () => {

                this.backOrClose();

                if(this.submitCallback) {
                    this.submitCallback();
                }
            });

            if(!this.sidebar.isOpen()) {
                this.sidebar.open();
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

        this.panels.markAsActive(id);
        this.panels.findActive().show();

        // set close triggers on sidebar. TODO: pass here type to switch templates x/terug/...
        this.sidebar.setBackButtonDisplay();
        this.listenForPanelTriggers();

        if(this.newPanelCallback) {
            this.newPanelCallback();
        }
    }

    backOrClose() {

        const previousId = this.panels.findActive().id;

        if(this.panels.findActive().parent) {
            this.show(this.panels.findActive().parent.url);
            this._reloadActivePanelSections();
            return;
        }

        this.panels.remove(previousId);
        this.sidebar.dom().querySelector(`[data-panel-id="${previousId}"]`).remove();

        // Only on the top level we close the sidebar
        // Check for unsaved content before clicking submit...
        this.sidebar.close();
        this._reset();
    }

    _reset() {
        this.panels.clear();

        // Remove all panels from dom
        this.sidebar.dom().innerHTML = '';
    }

    _reloadActivePanelSections() {
        Array.from(this.panels.findActive().el.querySelectorAll('[data-sidebar-component]')).forEach((el) => {
            const componentKey = el.getAttribute('data-sidebar-component');
            Api.get(this.panels.findActive().url, el, (data) => {
                let DOM = document.createElement('div');
                DOM.innerHTML = data;

                this.panels.activePanel.replaceComponent('[data-sidebar-component="' + componentKey + '"]' , DOM.querySelector('[data-sidebar-component="' + componentKey + '"]').innerHTML);
                this.listenForPanelTriggers();
            })
        });
    }

}

import {Api} from "./Api"

export default class Panels {
    constructor(sidebar, newPanelCallback, submitCallback) {
        this.sidebar = sidebar;
        this.newPanelCallback = newPanelCallback;
        this.submitCallback = submitCallback;

        this.panels = [];
        this.activePanel = null;

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
        if(this._find(id)) {
            this._activate(id);
            return;
        }

        this._addAndShowNewPanel(id, url);
    }

    _find(id) {
        console.dir(this.panels);
        return this.panels.find((panel) => panel.id === id );
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
                const previousId = this.activePanel.id;

                this.backOrClose();

                // On form submit we can safely remove current panel
                this._remove(previousId);

                if(this.submitCallback) {
                    this.submitCallback();
                }
            });

            if(!this.sidebar.isOpen()) {
                this.sidebar.open();
            }

            this.panels.push({
                id: id,
                url: url,
                parent: this.activePanel ? this.activePanel : null,
                dom: newPanelContainer
            });

            this._activate(id);
        })
    }

    _activate(id) {

        // Hide current active panel
        if(this.activePanel) {
            this.activePanel.dom.style.display = "none";
        }

        // Make our new panel the active one
        this.activePanel = this._find(id);
        this.activePanel.dom.style.display = "block";

        // set close triggers on sidebar. TODO: pass here type to switch templates x/terug/...
        this.sidebar.setBackButtonDisplay();

        this.listenForPanelTriggers();

        if(this.newPanelCallback) {
            this.newPanelCallback();
        }
    }

    _remove(id){
        const index = this.panels.findIndex((panel) => panel.id === id );
        delete this.panels[index];
        this.panels.splice(index,1);

        this.sidebar.dom().querySelector(`[data-panel-id="${id}"]`).remove();
    }

    backOrClose() {

        if(this.activePanel.parent) {
            this.show(this.activePanel.parent.url);
            this._reloadActivePanelSections();
            return;
        }

        // Only on the top level we close the sidebar
        // Check for unsaved content before clicking submit...
        this.sidebar.close();
        this._reset();
    }

    _reset() {
        this.panels = [];
        this.activePanel = null;

        // Remove all panels from dom
        this.sidebar.dom().innerHTML = '';
    }

    _reloadActivePanelSections() {
        Array.from(this.activePanel.dom.querySelectorAll('[data-sidebar-component]')).forEach((el) => {
            const componentKey = el.getAttribute('data-sidebar-component');
            Api.get(this.activePanel.url, el, (data) => {
                let DOM = document.createElement('div');
                DOM.innerHTML = data;

                this.activePanel.dom.querySelector('[data-sidebar-component="' + componentKey + '"]').innerHTML = DOM.querySelector('[data-sidebar-component="' + componentKey + '"]').innerHTML;
                this.listenForPanelTriggers();
            })
        });
    }

}

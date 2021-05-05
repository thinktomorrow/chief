import EventBus from '../../utilities/EventBus';

export default class {
    constructor(key, options) {
        /** Unique reference to this component type. */
        this.key = key;

        /** DOM selector for this component */
        this.componentSelector = options.componentSelector || `[data-${this.key}-component]`;

        /**
         * With this setting enables the component will be reloaded
         * via livewire after a related sidebarFormSubmitted event.
         */
        this.livewire = options.livewire || false;

        /**
         * The reloaded event that is expected from the livewire server. This emitted server event
         * tells the script that the livewire component has been reloaded. This event is used
         * as trigger by the sidebar to refresh sidebar event listeners in the component.
         */
        this.livewireEventKey = options.livewireEventKey || `${this.key}Reloaded`;

        /**
         * Option to ignore the panels created by this component
         * when a form is submitted on a deeper level.
         * @type {*|boolean}
         */
        this.closeOnPanelFormSubmit = options.closeOnPanelFormSubmit || false;

        if (options.events) {
            Object.keys(options.events).forEach((eventKey) => {
                EventBus.subscribe(eventKey, (panelData) => {
                    if (panelData.componentKey !== this.key) return;
                    options.events[eventKey](panelData);
                });
            });
        }

        if (options.onComponentCreation) {
            options.onComponentCreation();
        }

        this.onComponentReloadEvent = options.onComponentReload || function () {};
    }

    /**
     * Triggered by the sidebar after reload of the component.
     * Usually occurs after the livewire reload.
     */
    onComponentReload() {
        console.log(`component reload for ${this.key}`);
        this.onComponentReloadEvent();
    }

    /**
     * Retrieve the component DOM element from the given container element.
     *
     * @param container
     * @returns {*}
     */
    el(container) {
        return container.querySelector(this.componentSelector);
    }

    // onSidebarFormSubmitted(panelData) {
    //     console.log('YESSS', panelData);
    // }

    // loop over every component and check which one is active in current panel (or main (document))
    // These are the active components and are targets for trigger refreshes
    // On livewire reloads and such, we can trigger a 'refreshActiveComponents' type of method in Sidebar
    //

    // /**
    //  * Helper function to capitalise a string,
    //  * e.g. uppercase the first character of a string
    //  * @param string
    //  * @returns {string}
    //  * @private
    //  */
    // static _ucfirst(string) {
    //     return string.charAt(0).toUpperCase() + string.slice(1);
    // }
}

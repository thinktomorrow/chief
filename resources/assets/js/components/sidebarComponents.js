import Sidebar from './sidebar/Sidebar';
import Component from './sidebar/Component';
import AddFragment from './fragment/addFragment';
import SelectFragment from './fragment/selectFragment';
import initSortable from './fragment/sortableFragments';
import EventBus from '../utilities/EventBus';
import Collection from '../utilities/Collection';
import generateWireframeStyles from '../utilities/wireframe-styles';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    /** Fragments */

    /**
     * Keep track of already loaded selectFragments so we can reference the previous
     * one as the reference of the fragments component that should be used to
     * reflect the expected order value for the current fragments-create panel.
     */
    const loadedSelectFragments = new Collection();

    const fragmentsComponent = new Component('fragments', {
        livewire: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                // Trigger the sortable script to load for this panel
                EventBus.publish('fragmentSidebarPanelCreated', panelData);
            },
            sidebarPanelActivated: (panelData) => {
                console.log('triggered sidebarPanel', panelData.panel.el);

                const currentSelectFragment = new SelectFragment(panelData.panel.el, {
                    templateSelector: '#js-fragment-template-select-options-nested',
                });

                // If this panel contains a valid fragment container, we'll add
                // this current selectFragment reference to our stack as well
                if (currentSelectFragment.exists()) {
                    loadedSelectFragments.add({ id: panelData.panel.id, class: currentSelectFragment });
                }

                // Insert the order value of the former select fragment placeholder into this panel.
                // Not all panels need this value but the order value will not be inserted
                // if there is no order input present so it's safe to perform this each time.
                loadedSelectFragments.findParentOf(panelData.panel.id).class.insertOrderInPanelForm(panelData.panel.el);
            },
        },
        onComponentCreation: () => {
            loadedSelectFragments.clear().add({ id: 0, class: new SelectFragment(document) });
            initSortable();
            generateWireframeStyles();
        },
        onComponentReload: () => {
            loadedSelectFragments.clear().add({ id: 0, class: new SelectFragment(document) });
            initSortable();
            generateWireframeStyles();
        },
    });

    const fragmentSelectionComponent = new Component('selectFragment', {
        closeOnPanelFormSubmit: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                new AddFragment(panelData.panel.el);
                generateWireframeStyles();
            },
        },
    });

    const linksComponent = new Component('links', {
        livewire: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                console.log('special links panel created', panelData);
            },
            sidebarFormSubmitted: () => {
                const componentEl = statusComponent.el(document);
                const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

                livewireComponent.reload();
            },
        },
    });

    const statusComponent = new Component('status', {
        livewire: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                console.log('special status panel created', panelData);
            },
            sidebarFormSubmitted: () => {
                const componentEl = linksComponent.el(document);
                const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

                livewireComponent.reload();
            },
        },
    });

    const fieldComponents = [];
    document.querySelectorAll('[data-fields-component]').forEach((fieldComponentEl) => {
        fieldComponents.push(
            new Component(fieldComponentEl.getAttribute('data-fields-component'), {
                livewire: true,
                events: {
                    sidebarPanelCreated: (panelData) => {
                        console.log('special fields panel created', panelData);
                    },
                },
            })
        );
    });

    new Sidebar({
        debug: true,
        components: [
            linksComponent,
            statusComponent,
            fragmentsComponent,
            fragmentSelectionComponent,
            ...fieldComponents,
        ],
        reloadEvents: ['fragmentSelectionElementCreated'],
        reloadLivewireEvents: ['fragmentAdded'],
        events: {
            sidebarPanelCreated: () => {
                window.registerFieldToggles();
            },
            sidebarFormSubmitted: () => {
                //
            },
        },
    });
});

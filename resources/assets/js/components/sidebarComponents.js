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
    const fragmentsComponent = new Component('fragments', {
        livewire: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                // Trigger the sortable script to load for this panel
                EventBus.publish('fragmentSidebarPanelCreated', panelData);
            },
            sidebarPanelActivated: (panelData) => {
                console.log('triggered sidebarPanel', panelData.panel.el);

                // Todo: check if this sidebar contains fragments...
                new SelectFragment(panelData.panel.el);
            },
        },
        onComponentCreation: () => {
            // loadedSelectFragments.clear().add({ id: 0, class: new SelectFragment(document) });
            initSortable();
            generateWireframeStyles();
            console.log('component creation');
            new SelectFragment(document);
        },
        onComponentReload: () => {
            new SelectFragment(document);
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

import Sidebar from './sidebar/Sidebar';
import Component from './sidebar/Component';
import AddFragment from './fragment/addFragment';
import SelectFragment from './fragment/selectFragment';
import initSortable from './fragment/sortableFragments';
import EventBus from '../utilities/EventBus';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    /** Fragments */
    const fragmentsComponent = new Component('fragments', {
        livewire: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                console.log('sidebar fragments panel created');
                // Build the select fragment elements for the nested fragments components inside the panel

                // Trigger the sortable script to load for this panel
                EventBus.publish('fragmentSidebarPanelCreated', panelData);
            },
            sidebarPanelActivated: (panelData) => {
                console.log('triggered sidebarPanel', panelData.panel.el);
                new SelectFragment(panelData.panel.el, {
                    templateSelector: '#js-fragment-template-select-options-nested',
                });
            },
        },
        onComponentCreation: () => {
            console.log('component will be created');
            new SelectFragment(document);
            initSortable();
        },
    });

    const fragmentSelectionComponent = new Component('selectFragment', {
        closeOnPanelFormSubmit: true,
        events: {
            sidebarPanelCreated: (panelData) => {
                console.log('Select Fragments: panel created', panelData);
                new AddFragment(panelData.panel.el);
            },
        },
    });

    // EventBus.subscribe('fragmentSelectionElementCreated', () => {
    //     sidebar.listenForEvents();
    // });

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
        events: {
            sidebarPanelCreated: () => {
                console.log('Any sidebar panel was created.');
            },
            sidebarFormSubmitted: () => {
                //
            },
        },
    });
});

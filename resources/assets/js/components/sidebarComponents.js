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
        components: [linksComponent, fragmentsComponent, fragmentSelectionComponent, ...fieldComponents],
        reloadEvents: ['fragmentSelectionElementCreated'],
        events: {
            sidebarPanelCreated: () => {
                console.log('SIDEBAR always triggered');
                // // panel, triggerElement, triggerType
                // if (panelData.triggerType === 'links') {
                //     // Trigger js for submit form elements (these are used for the state transitions)
                //     FormSubmit.listen('[data-submit-form]', sidebarContainerEl);
                // }
            },
            sidebarFormSubmitted: () => {
                // form submit -> component die dit panel getrigged heeft moeten we refreshen.
                // (livewire) nested fragments: custom
                // if (panelData.triggerType === 'links') {
                //     linkComponent.reload();
                //     console.log('reload...');
                //     livewireComponent.reload();
                // }
            },
        },
    });
});

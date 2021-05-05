import Sidebar from './sidebar/Sidebar';
import Component from './sidebar/Component';
import AddFragment from './fragment/addFragment';
import SelectFragment from './fragment/selectFragment';
import initSortable from './fragment/sortableFragments';
import EventBus from '../utilities/EventBus';
import Collection from '../utilities/Collection';

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
                console.log('sidebar fragments panel created for ' + panelData.panel.id);
                // Build the select fragment elements for the nested fragments components inside the panel

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

                // Insert the order value of the former select fragment placeholder into this panel. Not all panels need this value
                // but the order value will not be inserted if there is no order input present so it's safe to perform this each time.
                loadedSelectFragments.findParentOf(panelData.panel.id).class.insertOrderInPanelForm(panelData.panel.el);
            },
        },
        onComponentCreation: () => {
            loadedSelectFragments.clear().add({ id: 0, class: new SelectFragment(document) });
            initSortable();
        },
        onComponentReload: () => {
            loadedSelectFragments.clear().add({ id: 0, class: new SelectFragment(document) });
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

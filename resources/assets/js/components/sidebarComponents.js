import Sidebar from './sidebar/Sidebar';
import Component from './sidebar/Component';
import AddFragment from './fragment/addFragment';
import SelectFragment from './fragment/selectFragment';
import EventBus from '../utilities/EventBus';
import generateWireframeStyles from '../utilities/wireframe-styles';
import initConditionalFields from '../utilities/conditional-fields';
import initSortableGroup from '../utilities/sortable-group';

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

                initSortableGroup('[data-sortable-fragments]', panelData.panel.el);
            },
            sidebarPanelActivated: (panelData) => {
                // Todo: check if this sidebar contains fragments...
                new SelectFragment(panelData.panel.el);
            },
        },
        onComponentCreation: () => {
            initSortableGroup('[data-sortable-fragments]');
            generateWireframeStyles();
            new SelectFragment(document);
        },
        onComponentReload: () => {
            new SelectFragment(document);
            initSortableGroup('[data-sortable-fragments]');
            generateWireframeStyles();
        },
    });

    // TODO: rename to addFragment component
    const fragmentAddComponent = new Component('addFragment', {
        closeOnPanelFormSubmit: true,
        events: {
            sidebarPanelActivated: (panelData) => {
                new AddFragment(panelData.panel.el);

                generateWireframeStyles();

                // submitFormOnChange('#fragment-select-existing-form', panelData.panel.el);
            },
        },
    });

    const linksComponent = new Component('links', {
        livewire: true,
        events: {
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
            sidebarFormSubmitted: () => {
                // Also reload the links component if this is present
                const componentEl = linksComponent.el(document);

                if (componentEl) {
                    const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

                    livewireComponent.reload();
                }
            },
        },
    });

    const fieldComponents = [];
    document.querySelectorAll('[data-fields-component]').forEach((fieldComponentEl) => {
        fieldComponents.push(
            new Component(fieldComponentEl.getAttribute('data-fields-component'), {
                livewire: true,
            })
        );
    });

    new Sidebar({
        debug: true,
        components: [linksComponent, statusComponent, fragmentsComponent, fragmentAddComponent, ...fieldComponents],
        reloadLivewireEvents: ['fragmentAdded'],
        events: {
            sidebarPanelActivated: () => {
                initConditionalFields();
            },
        },
    });
});

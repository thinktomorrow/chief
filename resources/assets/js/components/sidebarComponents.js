import Sidebar from './sidebar/Sidebar';
import Component from './sidebar/Component';
import AddFragment from './fragment/addFragment';
import SelectFragment from './fragment/selectFragment';
import initSortable from './fragment/sortableFragments';
import EventBus from '../utilities/EventBus';
import generateWireframeStyles from '../utilities/wireframe-styles';
// import { submitFormOnChange } from '../utilities/submit-form-on-event';

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
                // Todo: check if this sidebar contains fragments...
                new SelectFragment(panelData.panel.el);
            },
        },
        onComponentCreation: () => {
            initSortable();
            generateWireframeStyles();
            new SelectFragment(document);
        },
        onComponentReload: () => {
            new SelectFragment(document);
            initSortable();
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
            })
        );
    });

    new Sidebar({
        debug: true,
        components: [linksComponent, statusComponent, fragmentsComponent, fragmentAddComponent, ...fieldComponents],
        reloadLivewireEvents: ['fragmentAdded'],
        events: {
            sidebarPanelCreated: () => {
                window.registerFieldToggles();
            },
        },
    });
});

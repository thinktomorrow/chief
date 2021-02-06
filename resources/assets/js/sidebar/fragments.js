import Container from "./Container"
import PanelsManager from "./PanelsManager"
import {IndexSorting} from "../utilities/sortable";

// --------------------------------------------------------------------------------
// FRAGMENT JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {

    const sidebarEl = document.querySelector('[data-sidebar]');

    // Do not trigger the sidebar script is DOM element isn't present
    if(!sidebarEl) return;

    const livewireComponent = Livewire.find(document.querySelector('[data-fragments-component]').getAttribute('wire:id'));

    const sidebarPanels = (new PanelsManager(new Container(sidebarEl), function(panel){
        console.log('new panel ' + panel.id)
        initSortable('[data-sortable-fragments]', panel.el, {});
    }, function(){
        // trigger immediate reload of fragments component
        livewireComponent.reload();

        // TODO: set this in callback for when entire sidebar is closed.
        initSortable();
    }));

    sidebarPanels.init();

    Livewire.on('fragmentsReloaded', () => {
        sidebarPanels.scanForPanelTriggers();
    })

    function initSortable(selector = '[data-sortable-fragments]', container = document, options = {}) {

        // TODO: first remove existing sortable instances on these same selector els...

        Array.from(container.querySelectorAll(selector)).forEach((el) => {
            new IndexSorting({...{
                sortableGroupEl: el,
                endpoint: el.getAttribute('data-sortable-endpoint'),
                handle: '[data-sortable-handle]',
                isSorting: true,
            }, ...options})
        });
    }

    initSortable();

});

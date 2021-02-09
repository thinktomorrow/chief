import Container from "./Container"
import PanelsManager from "./PanelsManager"
import {IndexSorting} from "../utilities/sortable";

// --------------------------------------------------------------------------------
// FRAGMENT JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {

    const sidebarContainerEl = document.querySelector( '#js-sidebar-container');
    const componentEl = document.querySelector('[data-fragments-component]');

    // Do not trigger the sidebar script is DOM element isn't present
    if(!sidebarContainerEl || !componentEl) return;

    const livewireComponent = Livewire.find(componentEl.getAttribute('wire:id'));

    const fragmentPanelsManager = new PanelsManager('[data-sidebar-fragment-edit]', new Container(sidebarContainerEl), function(panel){
        console.log('new fragments panel ' + panel.id)
        initSortable('[data-sortable-fragments]', panel.el, {});
    }, function(){
        livewireComponent.reload();

        // TODO: set this in callback for when entire sidebar is closed.
        initSortable();
    });

    fragmentPanelsManager.init();

    Livewire.on('fragmentsReloaded', () => {
        fragmentPanelsManager.scanForPanelTriggers();
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

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

    const sidebarPanels = (new PanelsManager(new Container(sidebarEl), function(){
        console.log('new panel');
    }, function(){
        // trigger immediate reload of fragments component
        livewireComponent.reload();
    }));

    sidebarPanels.init();

    Livewire.on('fragmentsReloaded', () => {
        sidebarPanels.scanForPanelTriggers();
    })

    Array.from(document.querySelectorAll('[data-sortable-fragments]')).forEach((el) => {
        new IndexSorting({
            sortableGroupEl: el,
            endpoint: el.getAttribute('data-sortable-endpoint'),
            handle: '[data-sortable-handle]',
            isSorting: true,
        })
    });


});

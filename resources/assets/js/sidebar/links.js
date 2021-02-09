import Container from "./Container"
import PanelsManager from "./PanelsManager"

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {

    const sidebarContainerEl = document.querySelector( '#js-sidebar-container');
    const componentEl = document.querySelector('[data-links-component]');
    const livewireComponent = Livewire.find(componentEl.getAttribute('wire:id'));

    const linkPanelsManager = new PanelsManager('[data-sidebar-links-edit]', new Container(sidebarContainerEl), function(panel){
        console.log('new links panel ' + panel.id)
    }, function(){
        livewireComponent.reload();
    });

    linkPanelsManager.init();

    Livewire.on('linksReloaded', () => {
        linkPanelsManager.scanForPanelTriggers();
    })
});

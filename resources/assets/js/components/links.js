import Container from './sidebar/Container';
import PanelsManager from './sidebar/PanelsManager';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container'),
        componentEl = document.querySelector('[data-links-component]'),
        livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

    const linkPanelsManager = new PanelsManager('[data-sidebar-links-edit]', new Container(sidebarContainerEl), {
        onSubmitPanel: () => {
            livewireComponent.reload();
        },
        events: {
            // 'fragment-new': () => {
            //
            // },
        },
    });

    window.Livewire.on('linksReloaded', () => {
        linkPanelsManager.scanForPanelTriggers();
    });
});

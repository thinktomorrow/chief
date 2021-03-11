import Container from './sidebar/Container';
import PanelsManager from './sidebar/PanelsManager';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEl = document.querySelector('[data-links-component]');

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerEl || !componentEl) return;

    const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

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

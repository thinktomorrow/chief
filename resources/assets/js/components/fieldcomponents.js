import Container from './sidebar/Container';
import PanelsManager from './sidebar/PanelsManager';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEls = document.querySelectorAll('[data-fields-component]');

    Array.from(componentEls).forEach((el) => {
        const livewireComponent = window.Livewire.find(el.getAttribute('wire:id'));
        const linkPanelsManager = new PanelsManager(
            `[${el.getAttribute('data-fields-component')}]`,
            new Container(sidebarContainerEl),
            {
                panelFormSubmitted: () => {
                    livewireComponent.reload();
                },
                events: {
                    // 'fragmentSelectionElementCreated': () => {
                    //
                    // },
                },
            }
        );

        window.Livewire.on('fieldsComponentReloaded', () => {
            linkPanelsManager.scanForPanelTriggers();
        });
    });
});

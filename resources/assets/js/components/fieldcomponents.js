import Container from './sidebar/Container';
import PanelsManager from './sidebar/PanelsManager';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container'),
        componentEls = document.querySelectorAll('[data-fields-component]'),
        livewireComponent = Livewire.find(el.getAttribute('wire:id'));

    Array.from(componentEls).forEach((el) => {
        const linkPanelsManager = new PanelsManager(
            '[' + el.getAttribute('data-fields-component') + ']',
            new Container(sidebarContainerEl),
            {
                onSubmitPanel: () => {
                    livewireComponent.reload();
                },
                events: {
                    // 'fragment-new': () => {
                    //
                    // },
                },
            }
        );

        Livewire.on('fieldsComponentReloaded', () => {
            linkPanelsManager.scanForPanelTriggers();
        });
    });
});

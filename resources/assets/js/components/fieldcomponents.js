import Container from './sidebar/Container';
import PanelsManager from './sidebar/PanelsManager';

// --------------------------------------------------------------------------------
// LINKS JS --------------------------------------------------------------------
// --------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEls = document.querySelectorAll('[data-fields-component]');

    // Multiple componentEls with each their own sidebar instance ....
    // require route to edit - update, maybe via assistant FieldsAssistant::edit(model, fieldnames), update(),
    // Also how to show the content of these fields???? a field method:: renderAdminComponent() ???
    // <x-fields-component :tagged=seo></x-fields-component>

    Array.from(componentEls).forEach((el) => {
        const livewireComponent = Livewire.find(el.getAttribute('wire:id'));
        console.log(el.getAttribute('data-fields-component'));
        const linkPanelsManager = new PanelsManager(
            '[' + el.getAttribute('data-fields-component') + ']',
            new Container(sidebarContainerEl),
            {
                onNewPanel: (panel) => {
                    console.log('New fragments panel ' + panel.id);
                },
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

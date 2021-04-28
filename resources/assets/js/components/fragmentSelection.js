import EventBus from '../utilities/EventBus';
import Container from './sidebar/Container';
import PanelsManager from './sidebar/PanelsManager';

document.addEventListener('DOMContentLoaded', () => {
    const sidebarContainerElement = document.querySelector('#js-sidebar-container');
    const fragmentSelectionSidebarTriggerSelector = '[data-sidebar-fragment-selection]';

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerElement) return;

    const fragmentSelectionPanelsManager = new PanelsManager(
        fragmentSelectionSidebarTriggerSelector,
        new Container(sidebarContainerElement),
        {
            newPanelCreated: (panel) => {
                EventBus.publish('newFragmentSelectionPanelCreated', panel);
            },
            events: {
                fragmentSelectionElementCreated: () => {
                    fragmentSelectionPanelsManager.scanForPanelTriggers();
                },
            },
        }
    );
});
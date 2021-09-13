import EventBus from '../../../../../resources/assets/js/utilities/EventBus';
import RepeatField from './repeatfield';

function initRepeatFields() {
    const repeatContainerAttribute = 'data-repeat-container';
    const repeatContainers = Array.from(document.querySelectorAll(`[${repeatContainerAttribute}]`));

    repeatContainers.forEach((repeatContainer) => {
        const repeatContainerId = repeatContainer.getAttribute(repeatContainerAttribute);

        new RepeatField(repeatContainerId);
    });

    EventBus.subscribe('sidebarPanelActivated', (data) => {
        data.panel.el.querySelectorAll('[data-repeat-container]').forEach((el) => {
            console.log('setting up repeatfield for ', el.getAttribute('data-repeat-container'));
            new RepeatField(el.getAttribute('data-repeat-container'));
        });
    });
}

initRepeatFields();

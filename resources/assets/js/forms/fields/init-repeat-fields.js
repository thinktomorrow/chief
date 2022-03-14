import EventBus from '../../utilities/EventBus';
import Repeat from './repeat';

function initRepeatFieldsIn(container) {
    const repeatContainerSelector = '[data-repeat]';

    Array.from(container.querySelectorAll(repeatContainerSelector)).forEach((repeatElement) => {
        new Repeat(
            repeatElement.dataset.repeatEndpoint,
            repeatElement.id,
            '[data-repeat-section]',
            repeatElement.dataset.repeatSectionName
        );
    });
}

function initRepeatFields() {
    initRepeatFieldsIn(document);

    EventBus.subscribe('sidebarPanelActivated', (data) => {
        initRepeatFieldsIn(data.panel.el);
    });
}

export { initRepeatFields as default };

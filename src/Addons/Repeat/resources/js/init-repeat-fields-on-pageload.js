import EventBus from '../../../../../resources/assets/js/utilities/EventBus';
import { initRepeatFields } from './repeatfield';

function initRepeatFieldsOnPageLoad() {
    initRepeatFields(document);

    EventBus.subscribe('sidebarPanelActivated', (data) => {
        initRepeatFields(data.panel.el);
    });
}

export { initRepeatFieldsOnPageLoad as default };

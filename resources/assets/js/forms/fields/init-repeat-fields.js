import EventBus from '../../utilities/EventBus';
import { initRepeatFieldsIn } from './repeat';

function initRepeatFields() {
    initRepeatFieldsIn(document);

    EventBus.subscribe('sidebarPanelActivated', (data) => {
        initRepeatFieldsIn(data.panel.el);
    });
}

export { initRepeatFields as default };

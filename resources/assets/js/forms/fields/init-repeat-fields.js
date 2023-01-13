import EventBus from '../../utilities/EventBus';
import { initRepeatFieldsIn } from './repeat';

function initRepeatFields() {
    initRepeatFieldsIn(document);

    EventBus.subscribe('sidebarPanelActivated', (data) => {
        initRepeatFieldsIn(data.panel.el);
    });

    EventBus.subscribe('chief-form-refreshed', (e) => {
        initRepeatFieldsIn(e.element);
    });
}

export { initRepeatFields as default };

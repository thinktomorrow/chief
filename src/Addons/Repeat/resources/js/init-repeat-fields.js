import EventBus from '../../../../../resources/assets/js/utilities/EventBus';
import RepeatField from './repeatfield';

EventBus.subscribe('sidebarPanelActivated', (data) => {
    data.panel.el.querySelectorAll('[data-repeat-container]').forEach((el) => {
        console.log('setting up repeatfield for ', el.getAttribute('data-repeat-container'));
        new RepeatField(el.getAttribute('data-repeat-container'));
    });
});

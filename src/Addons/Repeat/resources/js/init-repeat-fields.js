import EventBus from '../../../../../resources/assets/js/utilities/EventBus';
import RepeatField from './repeatfield';

EventBus.subscribe('sidebarPanelActivated', (data) => {
    console.log('soo far..');
    data.panel.el.querySelectorAll('[data-repeat-container]').forEach((el) => {
        new RepeatField(el.getAttribute('data-repeat-container'));
    });
});

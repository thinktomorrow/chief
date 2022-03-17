import EventBus from './EventBus';

const initFormRefreshedNotifications = (notificationSelector = '[data-form-refreshed-notification]') => {
    EventBus.subscribe('form-refreshed', (data) => {
        const notification = data.element.querySelector(notificationSelector);

        if (notification) {
            notification.classList.remove('hidden');
        }
    });
};

export { initFormRefreshedNotifications as default };

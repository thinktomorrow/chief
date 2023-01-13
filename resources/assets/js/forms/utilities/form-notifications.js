import EventBus from '../../utilities/EventBus';

const initUnsavedNotification = (container, notificationSelector) => {
    const notification = container.querySelector(notificationSelector);

    if (notification) {
        container.addEventListener('change', () => {
            notification.classList.remove('hidden');
        });
    }
};

const initUnsavedNotifications = (formSelector, notificationSelector = '[data-form-unsaved-notification]') => {
    Array.from(document.querySelectorAll(formSelector)).forEach((form) => {
        initUnsavedNotification(form, notificationSelector);
    });

    EventBus.subscribe('chief-form-refreshed', (data) => {
        initUnsavedNotification(data.element, notificationSelector);
    });
};

const initRefreshedNotifications = (notificationSelector = '[data-form-refreshed-notification]') => {
    EventBus.subscribe('chief-form-refreshed', (data) => {
        const notification = data.element.querySelector(notificationSelector);

        if (notification) {
            notification.classList.remove('hidden');
        }
    });
};

const initFormNotifications = (formSelector = '[data-form]') => {
    initUnsavedNotifications(formSelector);
    initRefreshedNotifications();
};

export { initFormNotifications as default };

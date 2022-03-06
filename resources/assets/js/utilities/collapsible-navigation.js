import EventBus from './EventBus';

// TODO(tijs): on mobile the navigation is always expanded and works like a normal mobile navigation with hamburger icon
const initCollapsibleNavigation = (
    containerSelector = '[data-collapsible-navigation]',
    toggleSelector = '[data-toggle-collapsible-navigation]',
    changingElementsAttribute = 'data-class-on-collapse'
) => {
    const container = document.querySelector(containerSelector);
    const toggle = document.querySelector(toggleSelector);
    const changingElements = Array.from(document.querySelectorAll(`[${changingElementsAttribute}]`));
    let isCollapsed = localStorage.getItem('isNavigationCollapsed') || false;

    toggle.addEventListener('click', () => {
        container.classList.toggle('w-navigation');

        if (!isCollapsed) {
            // Publish event so dropdown.js will close all dropdowns
            EventBus.publish('collapsedNavigation');
        }

        changingElements.forEach((element) => {
            const classNames = element.getAttribute(changingElementsAttribute).split(' ');

            classNames.forEach((className) => {
                if (className === '') return;
                element.classList.toggle(className);
            });
        });

        isCollapsed = !isCollapsed;

        localStorage.setItem('isNavigationCollapsed', isCollapsed);
    });
};

export { initCollapsibleNavigation as default };

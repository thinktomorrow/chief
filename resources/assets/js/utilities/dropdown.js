import EventBus from './EventBus';

class Dropdown {
    constructor(toggleElement, dropdownElement) {
        this.toggleElement = toggleElement;
        this.dropdownElement = dropdownElement;

        this._init();
    }

    _init() {
        this.toggleElement.addEventListener('click', () => {
            this.dropdownElement.classList.toggle('hidden');
        });
    }

    close() {
        this.dropdownElement.classList.add('hidden');
    }
}

const initDropdowns = function (toggleAttribute = 'data-toggle-dropdown', dropdownAttribute = 'data-dropdown') {
    const toggleElements = Array.from(document.querySelectorAll(`[${toggleAttribute}]`));

    const dropdowns = toggleElements
        // Filter out dropdown toggles with on corresponding dropdown element
        .filter((toggleElement) => {
            const dropdownElementSelector = `[${dropdownAttribute}="${toggleElement.getAttribute(toggleAttribute)}"]`;
            return document.querySelector(dropdownElementSelector);
        })
        // Generate array with a Dropdown object for each valid toggle element
        .map((toggleElement) => {
            const dropdownElement = document.querySelector(
                `[${dropdownAttribute}="${toggleElement.getAttribute(toggleAttribute)}"]`
            );

            return new Dropdown(toggleElement, dropdownElement);
        });

    // If navigation is collapsed, close all dropdowns
    EventBus.subscribe('collapsedNavigation', () => {
        dropdowns.forEach((dropdown) => dropdown.close());
    });
};

export { initDropdowns as default };

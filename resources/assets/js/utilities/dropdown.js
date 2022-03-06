import EventBus from './EventBus';

class Dropdown {
    constructor(toggleElement, dropdownElement, nested) {
        this.toggleElement = toggleElement;
        this.dropdownElement = dropdownElement;
        this.nested = nested;

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

    isNested() {
        return this.nested;
    }
}

const initDropdowns = function (toggleAttribute = 'data-toggle-dropdown', dropdownAttribute = 'data-dropdown') {
    const toggleElements = Array.from(document.querySelectorAll(`[${toggleAttribute}]`));

    const dropdowns = toggleElements
        // Filter out dropdown toggles without a corresponding dropdown element
        .filter((toggleElement) => {
            const dropdownElementSelector = `[${dropdownAttribute}="${toggleElement.getAttribute(toggleAttribute)}"]`;
            return document.querySelector(dropdownElementSelector);
        })
        // Generate array with a Dropdown object for each valid toggle element
        .map((toggleElement) => {
            const dropdownElement = document.querySelector(
                `[${dropdownAttribute}="${toggleElement.getAttribute(toggleAttribute)}"]`
            );

            return new Dropdown(
                toggleElement,
                dropdownElement,
                ancestorHasAttribute(dropdownElement, dropdownAttribute)
            );
        });

    // When event is published, close all dropdowns
    EventBus.subscribe('closeAllDropdowns', () => {
        dropdowns.forEach((dropdown) => {
            if (!dropdown.isNested()) {
                dropdown.close();
            }
        });
    });
};

const ancestorHasAttribute = (element, attribute) => {
    const parent = element.parentElement;

    if (!parent) return false;

    if (parent.hasAttribute(attribute)) {
        return true;
    }

    return ancestorHasAttribute(parent, attribute);
};

export { initDropdowns as default };

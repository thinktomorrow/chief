import EventBus from '../../utilities/EventBus';

class Accordion {
    constructor(container) {
        this.toggleAttribute = 'data-accordion-toggle';
        this.contentAttribute = 'data-accordion-content';

        this.container = container;
        this.toggles = Array.from(container.querySelectorAll(`[${this.toggleAttribute}]`));
    }

    init() {
        this.toggles.forEach((toggle) => {
            const reference = toggle.getAttribute(this.toggleAttribute);
            const content = this.container.querySelector(`[${this.contentAttribute}="${reference}"]`);

            // If no content found for current toggle, return here
            if (!content) return;

            toggle.addEventListener('click', () => {
                this.constructor._toggleContent(content);
            });
        });
    }

    static _toggleContent(element) {
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    }
}

const createAccordions = (container, selector) => {
    Array.from(container.querySelectorAll(selector)).forEach((element) => {
        const accordion = new Accordion(element);

        accordion.init();
    });
};

const initAccordions = (selector = '[data-accordion]') => {
    document.addEventListener('DOMContentLoaded', () => {
        createAccordions(document, selector);
    });

    EventBus.subscribe('chief-form-refreshed', (data) => {
        createAccordions(data.element, selector);
    });

    EventBus.subscribe('sidebarPanelActivated', (data) => {
        createAccordions(data.panel.el, selector);
    });
};

export { initAccordions as default };

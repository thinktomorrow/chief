import _isEmpty from 'lodash/isEmpty';

class AccordionItem {
    constructor(container) {
        this.container = container;
        this.containerAttribute = 'data-accordion-item';
        this.toggleAttribute = 'data-accordion-item-toggle';
        this.contentAttribute = 'data-accordion-item-content';
        this.showIfOpenAttribute = 'data-accordion-item-show-if-open';
        this.showIfClosedAttribute = 'data-accordion-item-show-if-closed';
        this.disabledAttribute = 'data-accordion-item-disabled';

        this.id = this.container.getAttribute(this.containerAttribute);
    }

    init() {
        if (!this.container || !this.id) return;

        this.toggles = Array.from(this.container.querySelectorAll(`[${this.toggleAttribute}="${this.id}"]`));
        this.content = this.container.querySelector(`[${this.contentAttribute}="${this.id}"]`);
        this.showIfOpen = Array.from(this.container.querySelectorAll(`[${this.showIfOpenAttribute}="${this.id}"]`));
        this.showIfClosed = Array.from(this.container.querySelectorAll(`[${this.showIfClosedAttribute}="${this.id}"]`));
        this.disabled = this.container.getAttribute(this.disabledAttribute);

        if (this.disabled || _isEmpty(this.toggles) || !this.content) return;

        this._registerToggleEventListeners();

        this._toggleStateElements();
    }

    _registerToggleEventListeners() {
        this.toggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                this.content.classList.toggle('hidden');
                this._toggleStateElements();
            });
        });
    }

    _toggleStateElements() {
        if (this.content.classList.contains('hidden')) {
            this.showIfOpen.forEach((element) => {
                element.classList.add('hidden');
            });

            this.showIfClosed.forEach((element) => {
                element.classList.remove('hidden');
            });
        } else {
            this.showIfOpen.forEach((element) => {
                element.classList.remove('hidden');
            });

            this.showIfClosed.forEach((element) => {
                element.classList.add('hidden');
            });
        }
    }
}

const initAccordionItems = (container = document, itemSelector = '[data-accordion-item]') => {
    const items = Array.from(container.querySelectorAll(itemSelector));

    items.forEach((item) => {
        const accordionItem = new AccordionItem(item);

        accordionItem.init();
    });
};

export { initAccordionItems as default };

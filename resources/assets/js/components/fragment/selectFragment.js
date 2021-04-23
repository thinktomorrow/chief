import EventBus from '../../utilities/EventBus';

/**
 * Fragment new
 * show the available and shared fragment options
 */
export default class {
    constructor(container, fragmentsContainerSelector) {
        this.container = container;

        this.fragmentsContainerSelector = fragmentsContainerSelector;
        this.fragmentsContainer = this.container.querySelector(this.fragmentsContainerSelector);

        this.fragmentSelector = '[data-fragment]';
        this.triggerSelector = '[data-fragments-new-trigger]';
        this.selectionSelector = '[data-fragments-new-selection]';

        this._init();
    }

    _init() {
        this._addTriggerElements();
        this._activateTriggerElements();
        this._onlyShowClosestTriggerElement();

        const reloadEvents = ['sortable-stored', 'fragments-reloaded'];

        reloadEvents.forEach((event) => {
            EventBus.subscribe(event, () => {
                this.fragmentsContainer = this.container.querySelector(this.fragmentsContainerSelector);

                this._removeTriggerElements();
                this._addTriggerElements();
                this._activateTriggerElements();
                this._onlyShowClosestTriggerElement();
            });
        });

        EventBus.subscribe('fragments-new-panel', (panel) => {
            this._passNewFragmentOrderToPanel(panel);
        });
    }

    static _createTriggerElement() {
        const template = document.querySelector('#js-fragment-add-template');
        return template.firstElementChild.cloneNode(true);
    }

    static _createSelectionElement() {
        const template = document.querySelector('#js-fragment-selection-template');
        return template.firstElementChild.cloneNode(true);
    }

    _addTriggerElements() {
        const fragmentElements = Array.from(this.fragmentsContainer.querySelectorAll(this.fragmentSelector));

        // Add a trigger element if there no fragments exist yet
        if (fragmentElements.length === 0) {
            const triggerElement = this.constructor._createTriggerElement();

            this.fragmentsContainer.appendChild(triggerElement);

            return;
        }

        fragmentElements.forEach((element, index) => {
            const triggerElement = this.constructor._createTriggerElement();

            this.fragmentsContainer.insertBefore(triggerElement, element);

            if (index === fragmentElements.length - 1) {
                const lastTriggerElement = this.constructor._createTriggerElement();

                this.fragmentsContainer.insertBefore(lastTriggerElement, element.nextSibling);
            }
        });
    }

    _removeTriggerElements() {
        const triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            element.parentNode.removeChild(element);
        });
    }

    _activateTriggerElements() {
        const triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            this._activateTriggerElement(element);
        });
    }

    _activateTriggerElement(element) {
        element.addEventListener('click', () => {
            const selectionElement = this.constructor._createSelectionElement();
            const existingSelectionElement = this.fragmentsContainer.querySelector(this.selectionSelector);

            if (existingSelectionElement) {
                const triggerElement = this.constructor._createTriggerElement();

                this._activateTriggerElement(triggerElement);

                existingSelectionElement.parentNode.insertBefore(triggerElement, existingSelectionElement);
                existingSelectionElement.parentNode.removeChild(existingSelectionElement);
            }

            element.parentNode.insertBefore(selectionElement, element);
            element.parentNode.removeChild(element);

            EventBus.publish('fragment-new', selectionElement);
        });
    }

    _onlyShowClosestTriggerElement() {
        const fragmentElements = Array.from(this.fragmentsContainer.querySelectorAll(this.fragmentSelector));

        // Always show the triggerSelector when there are no fragmentElements
        if (fragmentElements.length === 0) {
            const triggerElement = this.fragmentsContainer.querySelector(this.triggerSelector);

            this.constructor._showTriggerElement(triggerElement);

            return;
        }

        fragmentElements.forEach((element) => {
            element.addEventListener('mouseover', (e) => {
                const rect = element.getBoundingClientRect();
                const centerY = rect.top + (rect.bottom - rect.top) / 2;

                this._hideAllTriggerElements();

                if (centerY > e.clientY) {
                    const triggerElement = this.constructor._getPreviousSiblingElement(element, this.triggerSelector);

                    if (!triggerElement) return;

                    this.constructor._showTriggerElement(triggerElement);
                } else {
                    const triggerElement = this.constructor._getNextSiblingElement(element, this.triggerSelector);

                    if (!triggerElement) return;

                    this.constructor._showTriggerElement(triggerElement);
                }
            });
        });
    }

    _hideAllTriggerElements() {
        const triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            this.constructor._hideTriggerElement(element);
        });
    }

    static _hideTriggerElement(triggerElement) {
        triggerElement.children[0].style.transform = 'scale(0)';
    }

    static _showTriggerElement(triggerElement) {
        triggerElement.children[0].style.transform = 'scale(1)';
    }

    static _getPreviousSiblingElement(element, selector) {
        let sibling = element.previousElementSibling;

        while (sibling) {
            if (sibling.matches(selector)) break;
            sibling = sibling.previousElementSibling;
        }

        return sibling;
    }

    static _getNextSiblingElement(element, selector) {
        let sibling = element.nextElementSibling;

        while (sibling) {
            if (sibling.matches(selector)) break;
            sibling = sibling.nextElementSibling;
        }

        return sibling;
    }

    _passNewFragmentOrderToPanel(panel) {
        const selectionElement = this.fragmentsContainer.querySelector(this.selectionSelector);

        if (selectionElement) {
            const order = this._getSelectionElementOrder(selectionElement);

            if (panel.el.querySelector('input[name="order"]')) {
                panel.el.querySelector('input[name="order"]').value = order;
            }
        }
    }

    _getSelectionElementOrder(node) {
        const nextFragmentElement = this.constructor._getNextSiblingElement(node, this.fragmentSelector);

        const fragmentsContainerChildren = Array.from(this.fragmentsContainer.children);
        const fragmentElements = fragmentsContainerChildren.filter((element) => element.matches(this.fragmentSelector));

        let order = fragmentElements.length;

        fragmentElements.forEach((fragmentElement, index) => {
            if (fragmentElement === nextFragmentElement) {
                order = index;
            }
        });

        return order;
    }
}

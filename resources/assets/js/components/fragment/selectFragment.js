import _isEmpty from 'lodash/isEmpty';
import EventBus from '../../utilities/EventBus';

/**
 * Fragment new
 * show the available and shared fragment options
 */
export default class {
    constructor(container, fragmentsContainerSelector = '[data-fragments-container]') {
        this.container = container;

        this.fragmentsContainerSelector = fragmentsContainerSelector;
        this.fragmentsContainer = this.container.querySelector(this.fragmentsContainerSelector);

        this.fragmentSelector = '[data-fragment]';
        this.triggerSelector = '[data-fragment-trigger-element]';
        this.selectionSelector = '[data-fragment-selection-element]';
        this.selectionCloseTriggerSelector = '[data-fragment-selection-element-close]';

        this._init();
    }

    _init() {
        this._addTriggerElements();
        this._activateTriggerElements();
        this._onlyShowClosestTriggerElement();

        const reloadEvents = ['sortable-stored', 'fragmentsReloaded'];

        reloadEvents.forEach((event) => {
            EventBus.subscribe(event, () => {
                // Needs to be redefined after Livewire rebuild the element with the added fragment
                this.fragmentsContainer = this.container.querySelector(this.fragmentsContainerSelector);

                this._removeTriggerElements();
                this._addTriggerElements();
                this._activateTriggerElements();
                this._onlyShowClosestTriggerElement();
            });
        });

        EventBus.subscribe('newFragmentPanelCreated', (panel) => {
            this._passNewFragmentOrderToPanel(panel);
        });
    }

    _addTriggerElements() {
        const fragmentElements = Array.from(this.fragmentsContainer.querySelectorAll(this.fragmentSelector));

        // Add a selection element instead if no fragments exist yet
        if (_isEmpty(fragmentElements)) {
            const newSelectionElement = this._createSelectionElement(false);

            this.fragmentsContainer.appendChild(newSelectionElement);

            EventBus.publish('fragmentSelectionElementCreated', newSelectionElement);

            return;
        }

        // Add a trigger element before every fragment element
        fragmentElements.forEach((element, index) => {
            const triggerElement = this.constructor._createTriggerElement();

            this.fragmentsContainer.insertBefore(triggerElement, element);

            // Also add a trigger element after the last fragment element
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
            this._purgeFragmentsContainerFromSelectionElements();

            const newSelectionElement = this._createSelectionElement();

            this.fragmentsContainer.replaceChild(newSelectionElement, element);

            EventBus.publish('fragmentSelectionElementCreated', newSelectionElement);
        });
    }

    _purgeFragmentsContainerFromSelectionElements() {
        const existingSelectionElements = Array.from(this.fragmentsContainer.querySelectorAll(this.selectionSelector));

        existingSelectionElements.forEach((element) => {
            const newTriggerElement = this.constructor._createTriggerElement();
            this._activateTriggerElement(newTriggerElement);

            this.fragmentsContainer.replaceChild(newTriggerElement, element);
        });
    }

    _hideAllTriggerElements() {
        const triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            this.constructor._hideTriggerElement(element);
        });
    }

    _onlyShowClosestTriggerElement() {
        const fragmentElements = Array.from(this.fragmentsContainer.querySelectorAll(this.fragmentSelector));

        // If there are not fragments yet, there are also no triggerElements
        if (_isEmpty(fragmentElements)) return;

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

    _createSelectionElement(isClosable = true) {
        const template = document.querySelector('#js-fragment-selection-template');
        const element = template.firstElementChild.cloneNode(true);
        const elementCloseTrigger = element.querySelector(this.selectionCloseTriggerSelector);

        if (elementCloseTrigger) {
            if (isClosable) {
                elementCloseTrigger.addEventListener('click', () => {
                    const newTriggerElement = this.constructor._createTriggerElement();

                    this._activateTriggerElement(newTriggerElement);

                    element.parentNode.replaceChild(newTriggerElement, element);
                });
            } else {
                elementCloseTrigger.style.display = 'none';
            }
        }

        return element;
    }

    static _createTriggerElement() {
        const template = document.querySelector('#js-fragment-add-template');
        return template.firstElementChild.cloneNode(true);
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
}

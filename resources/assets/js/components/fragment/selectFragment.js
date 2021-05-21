import _isEmpty from 'lodash/isEmpty';
import EventBus from '../../utilities/EventBus';

/**
 * Fragment new
 * show the available and shared fragment options
 */
export default class {
    constructor(container, options = {}) {
        this.container = container;
        this.fragmentsContainerSelector = options.fragmentsContainerSelector || '[data-fragments-container]';
        this.templateSelector = options.templateSelector || '#js-fragment-template-select-options-main';

        this.fragmentSelector = '[data-fragment]';
        this.triggerSelector = '[data-fragment-trigger-element]';
        this.selectionSelector = '[data-fragment-selection-element]';
        this.selectionCloseTriggerSelector = '[data-fragment-selection-element-close]';

        this.elementExists = true;

        if (!this.container || !this.findFragmentsContainer()) {
            this.elementExists = false;
            return;
        }

        this._init();
    }

    exists() {
        return this.elementExists;
    }

    findFragmentsContainer() {
        return this.container.querySelector(this.fragmentsContainerSelector);
    }

    _init() {
        this._build();

        const reloadEvents = ['sortableStored', 'fragmentsReloaded'];

        reloadEvents.forEach((event) => {
            EventBus.subscribe(event, () => {
                console.log('rebuilding');
                this._build();
            });
        });
    }

    _build() {
        this.fragmentsContainer = this.findFragmentsContainer();
        this._removeTriggerElements();
        this._addTriggerElements();
        this._activateTriggerElements();
        this._onlyShowClosestTriggerElement();
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
            this.fragmentsContainer.removeChild(element);
        });
    }

    _activateTriggerElements() {
        const triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            this._activateTriggerElement(element);
        });
    }

    _activateTriggerElement(element) {
        element.addEventListener('click', (e) => {
            // Temporary fix for problem where after adding/deleting 2 fragments,
            // fragments start acting as trigger elements as well.
            if (!e.currentTarget.matches(this.triggerSelector)) {
                console.log(`
                    Prevented handler from being triggered by fragment.
                    This handler should only be triggered by dedicated triggers ...
                `);
                return;
            }

            this._purgeFragmentsContainerFromSelectionElements();

            const newSelectionElement = this._createSelectionElement();

            this.fragmentsContainer.insertBefore(newSelectionElement, element);
            this.fragmentsContainer.removeChild(element);
            // this.fragmentsContainer.replaceChild(newSelectionElement, element);

            this.addOrderToExistingFragmentLink();

            EventBus.publish('fragmentSelectionElementCreated', newSelectionElement);
        });
    }

    _purgeFragmentsContainerFromSelectionElements() {
        const existingSelectionElements = Array.from(this.fragmentsContainer.querySelectorAll(this.selectionSelector));

        existingSelectionElements.forEach((element) => {
            const newTriggerElement = this.constructor._createTriggerElement();
            this._activateTriggerElement(newTriggerElement);

            this.fragmentsContainer.insertBefore(newTriggerElement, element);
            this.fragmentsContainer.removeChild(element);
            // this.fragmentsContainer.replaceChild(newTriggerElement, element);
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

    insertOrderInPanelForm(panelElement) {
        const selectionElement = this.fragmentsContainer.querySelector(this.selectionSelector);

        if (selectionElement) {
            const order = this._getSelectionElementOrder(selectionElement);

            if (!panelElement.querySelector('input[name="order"]')) return;

            panelElement.querySelector('input[name="order"]').setAttribute('value', order);
        }
    }

    addOrderToExistingFragmentLink() {
        const selectionElement = this.fragmentsContainer.querySelector(this.selectionSelector);

        if (selectionElement) {
            const order = this._getSelectionElementOrder(selectionElement);
            console.log(order);
            selectionElement.querySelectorAll('[data-sidebar-trigger]').forEach((el) => {
                console.log(el);
                if (el.hasAttribute('href')) {
                    el.setAttribute('href', `${el.getAttribute('href')}?order=${order}`);
                }
            });
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
        const template = document.querySelector(this.templateSelector);

        const newSelectionElement = template.firstElementChild.cloneNode(true);
        const elementCloseTrigger = newSelectionElement.querySelector(this.selectionCloseTriggerSelector);

        if (isClosable) {
            elementCloseTrigger.addEventListener('click', () => {
                const newTriggerElement = this.constructor._createTriggerElement();

                this._activateTriggerElement(newTriggerElement);

                this.fragmentsContainer.insertBefore(newTriggerElement, newSelectionElement);
                this.fragmentsContainer.removeChild(newSelectionElement);
                // element.parentNode.replaceChild(newTriggerElement, element);
            });
        } else {
            elementCloseTrigger.style.display = 'none';
        }

        return newSelectionElement;
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

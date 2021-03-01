import EventBus from '../../utilities/EventBus';

/**
 * Fragment new
 * submit fragment post request and grab results
 */
export default class {
    constructor(container, fragmentsContainer) {
        this.container = container;
        this.fragmentsContainer = fragmentsContainer;

        this.fragmentSelector = '[data-fragment]';
        this.triggerSelector = '[data-fragments-new-trigger]';
        this.selectionSelector = '[data-fragments-new-selection]';

        this._init();
    }

    _init() {
        this._addTriggerElements();
        this._activateTriggerElements();

        let reloadEvents = ['sortable-stored', 'fragments-reloaded'];

        reloadEvents.forEach((event) => {
            EventBus.subscribe(event, () => {
                this._removeTriggerElements();
                this._addTriggerElements();
                this._activateTriggerElements();
            });
        });

        EventBus.subscribe('fragments-new-panel', (panel) => {
            this._passNewFragmentOrderToPanel(panel);
        });

        this._onlyShowClosestTriggerElement();
    }

    _createTriggerElement() {
        const template = document.querySelector('#js-fragment-add-template');
        return template.firstElementChild.cloneNode(true);
    }

    _createSelectionElement() {
        const template = document.querySelector('#js-fragment-selection-template');
        return template.firstElementChild.cloneNode(true);
    }

    _addTriggerElements() {
        let fragmentElements = Array.from(this.fragmentsContainer.querySelectorAll(this.fragmentSelector));

        fragmentElements.forEach((element, index) => {
            let triggerElement = this._createTriggerElement();

            this.fragmentsContainer.insertBefore(triggerElement, element);

            if (index === fragmentElements.length - 1) {
                let triggerElement = this._createTriggerElement();

                this.fragmentsContainer.insertBefore(triggerElement, element.nextSibling);
            }
        });
    }

    _removeTriggerElements() {
        let triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            element.parentNode.removeChild(element);
        });
    }

    _activateTriggerElements() {
        let triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            this._activateTriggerElement(element);
        });
    }

    _activateTriggerElement(element) {
        element.addEventListener('click', () => {
            let selectionElement = this._createSelectionElement();
            let existingSelectionElement = this.fragmentsContainer.querySelector(this.selectionSelector);

            if (existingSelectionElement) {
                let triggerElement = this._createTriggerElement();

                this._activateTriggerElement(triggerElement);

                existingSelectionElement.parentNode.insertBefore(triggerElement, existingSelectionElement);
                existingSelectionElement.parentNode.removeChild(existingSelectionElement);
            }

            element.parentNode.insertBefore(selectionElement, element);
            element.parentNode.removeChild(element);

            EventBus.publish('fragment-new');
        });
    }

    _onlyShowClosestTriggerElement() {
        let fragmentElements = Array.from(this.fragmentsContainer.querySelectorAll(this.fragmentSelector));

        fragmentElements.forEach((element) => {
            element.addEventListener('mouseover', (e) => {
                let rect = element.getBoundingClientRect();
                let centerY = rect.top + (rect.bottom - rect.top) / 2;

                this._hideAllTriggerElements();
                if (centerY > e.clientY) {
                    let triggerElement = this._getPreviousSiblingElement(element, this.triggerSelector);

                    if (!triggerElement) return;

                    this._showTriggerElement(triggerElement);
                } else {
                    let triggerElement = this._getNextSiblingElement(element, this.triggerSelector);

                    if (!triggerElement) return;

                    this._showTriggerElement(triggerElement);
                }
            });
        });
    }

    _hideAllTriggerElements() {
        let triggerElements = Array.from(this.fragmentsContainer.querySelectorAll(this.triggerSelector));

        triggerElements.forEach((element) => {
            this._hideTriggerElement(element);
        });
    }

    _hideTriggerElement(triggerElement) {
        triggerElement.children[0].style.transform = 'scale(0)';
    }

    _showTriggerElement(triggerElement) {
        triggerElement.children[0].style.transform = 'scale(1)';
    }

    _getPreviousSiblingElement(element, selector) {
        let sibling = element.previousElementSibling;

        while (sibling) {
            if (sibling.matches(selector)) break;
            sibling = sibling.previousElementSibling;
        }

        return sibling;
    }

    _getNextSiblingElement(element, selector) {
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
            let order = this._getSelectionElementOrder(selectionElement);

            if (panel.el.querySelector('input[name="order"]')) {
                panel.el.querySelector('input[name="order"]').value = order;
            }
        }
    }

    _getSelectionElementOrder(node) {
        let nextFragmentElement = this._getNextSiblingElement(node, this.fragmentSelector);

        let fragmentElements = Array.from(this.fragmentsContainer.children).filter((element) =>
            element.matches(this.fragmentSelector)
        );

        let order = fragmentElements.length;

        fragmentElements.forEach((fragmentElement, index) => {
            if (fragmentElement === nextFragmentElement) {
                order = index;
                return;
            }
        });

        return order;
    }
}

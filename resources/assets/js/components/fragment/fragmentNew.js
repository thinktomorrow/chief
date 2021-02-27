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

        this._onlyShowClosestTriggerElement();

        EventBus.subscribe('sortable-stored', () => {
            this._removeTriggerElements();

            this._addTriggerElements();
            this._activateTriggerElements();
        });
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
                    let triggerElement = this._getTopTriggerElement(element);

                    if (!triggerElement) return;

                    this._showTriggerElement(triggerElement);
                } else {
                    let triggerElement = this._getBottomTriggerElement(element);

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

    _getTopTriggerElement(fragmentElement) {
        // Get the next sibling element
        var sibling = fragmentElement.previousElementSibling;

        // If the sibling matches our selector, use it
        // If not, jump to the next sibling and continue the loop
        while (sibling) {
            if (sibling.matches(this.triggerSelector)) return sibling;
            sibling = sibling.previousElementSibling;
        }
    }

    _getBottomTriggerElement(fragmentElement) {
        // Get the next sibling element
        var sibling = fragmentElement.nextElementSibling;

        // If the sibling matches our selector, use it
        // If not, jump to the next sibling and continue the loop
        while (sibling) {
            if (sibling.matches(this.triggerSelector)) return sibling;
            sibling = sibling.nextElementSibling;
        }
    }

    // constructor(container, fragmentsContainer) {
    //     this.container = container || document;
    //     this.fragmentsContainer = fragmentsContainer;
    //     this.triggerAttribute = 'data-fragments-new';
    //     this.selectElAttribute = 'data-fragments-new-selection';

    //     this.init();
    // }

    // init() {
    //     // Register unique trigger handler
    //     this.handle = (event) => this._handleTrigger(event);

    //     EventBus.subscribe('fragments-new-panel', (panel) => {
    //         this._onNewPanel(panel);
    //     });

    //     EventBus.subscribe('fragments-reloaded', (panel) => {
    //         this.scanForTriggers();
    //     });

    //     this.scanForTriggers();
    // }

    // scanForTriggers() {
    //     Array.from(this.container.querySelectorAll(`[${this.triggerAttribute}]`)).forEach((el) => {
    //         el.removeEventListener('click', this.handle);
    //         el.addEventListener('click', this.handle);
    //     });
    // }

    // _onNewPanel(panel) {
    //     const fragmentSelectionElement = document.querySelector(`[${this.selectElAttribute}]`);
    //     if (fragmentSelectionElement) {
    //         let order = this._getChildIndex(fragmentSelectionElement);
    //         if (panel.el.querySelector('input[name="order"]')) {
    //             panel.el.querySelector('input[name="order"]').value = order;
    //         }
    //     }
    // }

    // _handleTrigger(event) {
    //     event.preventDefault();

    //     const triggerElement = event.target.hasAttribute(this.triggerAttribute)
    //         ? event.target
    //         : event.target.closest(`[${this.triggerAttribute}]`);

    //     if (!triggerElement) return;

    //     // Remove any existing selection els - only want to display one.
    //     Array.from(this.fragmentsContainer.querySelectorAll(`[${this.selectElAttribute}]`)).forEach((el) => {
    //         el.remove();
    //     });

    //     // Create the new selection el from our template
    //     const selectionEl = this._createSelectionEl();

    //     this._insertSelectionEl(selectionEl, triggerElement);

    //     this._showAllTriggers();
    //     this._hideCurrentTrigger(triggerElement);

    //     EventBus.publish('fragment-new');
    // }

    // _createSelectionEl() {
    //     const template = document.querySelector('#js-fragment-selection-template');
    //     const selectionElement = template.firstElementChild.cloneNode(true);
    //     return selectionElement;
    // }

    // _insertSelectionEl(element, target) {
    //     let targetElementContainer = target.parentNode.hasAttribute('data-sortable-id')
    //         ? target.parentNode.parentNode
    //         : target.parentNode;
    //     let targetElement = target.parentNode.hasAttribute('data-sortable-id') ? target.parentNode : target;

    //     targetElementContainer.insertBefore(element, targetElement.nextSibling);
    // }

    // _getChildIndex(node) {
    //     return Array.prototype.indexOf.call(node.parentElement.children, node);
    // }

    // _hideCurrentTrigger(element) {
    //     element.classList.add('hidden');
    // }

    // _showAllTriggers() {
    //     Array.from(this.container.querySelectorAll(`[${this.triggerAttribute}]`)).forEach((el) => {
    //         el.classList.remove('hidden');
    //     });
    // }
}

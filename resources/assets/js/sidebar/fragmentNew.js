/**
 * Fragment new
 * submit fragment post request and grab results
 */
export default class {
    constructor(container, fragmentsContainer, onSubmit) {
        this.container = container || document;
        this.fragmentsContainer = fragmentsContainer;
        this.onSubmit = onSubmit;
        this.triggerAttribute = 'data-fragments-new';
        this.selectElAttribute = 'data-fragments-new-selection';
    }

    init() {
        // Register unique trigger handler
        this.handle = (event) => this._handleTrigger(event);

        this.scanForTriggers();
    }

    scanForTriggers() {
        Array.from(this.container.querySelectorAll(`[${this.triggerAttribute}]`)).forEach((el) => {
            el.removeEventListener('click', this.handle);
            el.addEventListener('click', this.handle);
        });
    }

    onNewPanel(panel) {
        const fragmentSelectionElement = document.querySelector(`[${this.selectElAttribute}]`);
        if (fragmentSelectionElement) {
            let order = _getChildIndex(fragmentSelectionElement);
            if (panel.el.querySelector('input[name="order"]')) {
                panel.el.querySelector('input[name="order"]').value = order;
            }
        }
    }

    _handleTrigger(event) {
        event.preventDefault();

        const el = event.target.hasAttribute(this.triggerAttribute)
            ? event.target
            : event.target.closest(`[${this.triggerAttribute}]`);

        if (!el) return;

        // Remove any existing selection els - only want to display one.
        Array.from(this.fragmentsContainer.querySelectorAll(`[${this.selectElAttribute}]`)).forEach((el) => {
            el.remove();
        });

        // Create the new selection el from our template
        const selectionEl = createSelectionEl();
        insertSelectionEl(selectionEl, trigger);

        if (this.onClick) {
            this.onClick(selectionEl);
        }
    }

    _createSelectionEl() {
        const template = document.querySelector('#js-fragment-selection-template');
        const el = template.firstElementChild.cloneNode(true);
        console.log(el);
        return el;
    }

    _insertSelectionEl(element, trigger) {
        let insertBeforeTarget = trigger.getAttribute('data-fragments-new-position') === 'before';
        let targetElement = document.querySelector(
            `[data-sortable-id="${trigger.getAttribute('data-fragments-new')}"]`
        );

        if (insertBeforeTarget) {
            targetElement.parentNode.insertBefore(element, targetElement);
        } else {
            targetElement.parentNode.insertBefore(element, targetElement.nextSibling);
        }
    }

    _getChildIndex(node) {
        return Array.prototype.indexOf.call(node.parentElement.children, node);
    }
}

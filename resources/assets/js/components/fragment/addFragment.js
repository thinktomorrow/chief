import Api from '../sidebar/Api';
import EventBus from '../../utilities/EventBus';

/**
 * Fragment add
 * Immediately add an existing fragment to the given context.
 */
export default class {
    constructor(container) {
        this.container = container || document;
        this.postActionAttribute = 'data-fragments-add';

        this.init();
    }

    init() {
        this.handle = (event) => this._handleTrigger(event);

        EventBus.subscribe('newFragmentPanelCreated', (panel) => {
            this._scanForTriggersIn(panel.el);
        });

        this.scanForTriggers();
    }

    scanForTriggers() {
        this._scanForTriggersIn(this.container);
    }

    _scanForTriggersIn(element) {
        Array.from(element.querySelectorAll(`[${this.postActionAttribute}]`)).forEach((el) => {
            el.removeEventListener('click', this.handle);
            el.addEventListener('click', this.handle);
        });
    }

    _handleTrigger(event) {
        event.preventDefault();

        const el = event.target.hasAttribute(this.postActionAttribute)
            ? event.target
            : event.target.closest(`[${this.postActionAttribute}]`);
        const action = el ? el.getAttribute(this.postActionAttribute) : null;

        if (!action) return;

        Api.post(action, {}, (data) => {
            EventBus.publish('fragmentAdded', {
                componentKey: 'addFragment',
            });
        });
    }
}

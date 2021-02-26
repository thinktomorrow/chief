import { Api } from '../sidebar/Api';
import EventBus from '../sidebar/EventBus';

/**
 * Fragment add
 * submit fragment post request and grab results
 */
export default class {
    constructor(container) {
        this.container = container || document;
        this.postActionAttribute = 'data-fragments-add';

        this.init();
    }

    init() {
        // Register unique trigger handler
        this.handle = (event) => this._handleTrigger(event);

        EventBus.subscribe('fragment-new', () => {
            this.scanForTriggers();
        });

        this.scanForTriggers();
    }

    scanForTriggers() {
        Array.from(this.container.querySelectorAll(`[${this.postActionAttribute}]`)).forEach((el) => {
            el.removeEventListener('click', this.handle);
            el.addEventListener('click', this.handle);
        });
    }

    _handleTrigger(event) {
        event.preventDefault();
        const el = event.target.hasAttribute(this.postActionAttribute)
                ? event.target
                : event.target.closest(`[${this.postActionAttribute}]`),
            action = el ? el.getAttribute(this.postActionAttribute) : null;

        if (!action) return;

        Api.submit('POST', action, {}, (data) => {
            this.scanForTriggers();

            EventBus.publish('fragment-add', data);
        });
    }
}

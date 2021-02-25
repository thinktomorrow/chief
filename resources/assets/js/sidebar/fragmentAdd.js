import { Api } from './Api';

/**
 * Fragment add
 * submit fragment post request and grab results
 */
export default class {
    constructor(container, onSubmit) {
        this.container = container || document;
        this.onSubmit = onSubmit;
        this.postActionAttribute = 'data-fragments-add';
    }

    init() {
        // Register unique trigger handler
        this.handle = (event) => this._handleTrigger(event);

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
            if (this.onSubmit) {
                this.onSubmit(data);
            }
            console.log(data);
        });
    }
}

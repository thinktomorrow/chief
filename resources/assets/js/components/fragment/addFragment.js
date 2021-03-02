import Api from '../sidebar/Api';
import EventBus from '../../utilities/EventBus';

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

        // EventBus.subscribe('fragment-new', () => {
        //     this.scanForTriggers();
        // });

        EventBus.subscribe('fragment-new', (selectionEl) => {
            console.log('selection...');
            this._scanForTriggersIn(selectionEl);
        });

        EventBus.subscribe('fragments-new-panel', (panel) => {
            console.log('panel...', panel);
            this._scanForTriggersIn(panel.el);
        });

        this.scanForTriggers();
    }

    scanForTriggers() {
        this._scanForTriggersIn(this.container);
    }

    _scanForTriggersIn(element) {
        console.log(element);
        console.log(this.postActionAttribute, element.querySelectorAll(`[${this.postActionAttribute}]`));

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

        console.log(action);

        if (!action) return;

        Api.submit('POST', action, {}, (data) => {
            this.scanForTriggers();

            EventBus.publish('fragment-add', data);
        });
    }
}

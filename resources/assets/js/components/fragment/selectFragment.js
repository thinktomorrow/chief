/**
 * Fragment new
 * show the available and shared fragment options
 */
export default class {
    constructor(container) {
        this.container = container;

        this.elementSelector = '[data-fragment-select]';
        this.optionsSelector = '[data-fragment-select-options]';
        this.openSelector = '[data-fragment-select-open]';
        this.closeSelector = '[data-fragment-select-close]';

        /**
         * Register unique trigger handlers
         *
         * if we'd call the method directly as callback, it cannot be
         * removed as it is regarded to be a different function.
         */
        this.handleOpenReference = (event) => this._handleOpen(event);
        this.handleCloseReference = (event) => this._handleClose(event);

        this.listenForEvents();
    }

    listenForEvents() {
        this.container.querySelectorAll(this.openSelector).forEach((triggerEl) => {
            triggerEl.removeEventListener('click', this.handleOpenReference);
            triggerEl.addEventListener('click', this.handleOpenReference);
        });

        this.container.querySelectorAll(this.closeSelector).forEach((trigger) => {
            trigger.removeEventListener('click', this.handleCloseReference);
            trigger.addEventListener('click', this.handleCloseReference);
        });
    }

    _closeAll() {
        this.container.querySelectorAll(this.optionsSelector).forEach((element) => {
            element.classList.add('hidden');
        });
    }

    _handleOpen(event) {
        event.preventDefault();

        const trigger = event.currentTarget;
        const element = trigger.closest(this.elementSelector);
        const optionsElement = element.querySelector(this.optionsSelector);

        this._closeAll();

        optionsElement.classList.remove('hidden');

        this._addOrderToExistingFragmentLink(element);
    }

    _handleClose(event) {
        event.preventDefault();

        const trigger = event.currentTarget;
        const element = trigger.closest(this.elementSelector).querySelector(this.optionsSelector);

        element.classList.add('hidden');
    }

    _addOrderToExistingFragmentLink(element) {
        const order = this._detectOrder(element);

        element.querySelectorAll('[data-sidebar-trigger]').forEach((el) => {
            if (el.hasAttribute('href')) {
                el.setAttribute('href', this.constructor._getUriWithParam(el.getAttribute('href'), { order }));
            }
        });
    }

    static _getUriWithParam(baseUrl, params) {
        const Url = new URL(baseUrl);
        const urlParams = new URLSearchParams(Url.search);
        for (const key in params) {
            if (Object.prototype.hasOwnProperty.call(params, key) && params[key] !== undefined) {
                urlParams.set(key, params[key]);
            }
        }
        Url.search = urlParams.toString();
        return Url.toString();
    }

    _detectOrder(element) {
        let order = 0;
        let _break = false;

        this.container.querySelectorAll(this.elementSelector).forEach((item) => {
            if (item === element) _break = true;
            if (!_break) {
                order++;
            }
        });

        return order;
    }
}

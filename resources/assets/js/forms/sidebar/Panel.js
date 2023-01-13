export default class {
    constructor(id, url, parent, el, options = {}) {
        this.id = id;
        this.url = url;
        this.parent = parent;
        this.el = el;
        this.options = options;
    }

    show() {
        this.el.style.display = 'block';
    }

    hide() {
        this.el.style.display = 'none';
    }

    getTags() {
        return this.options.tags || [];
    }

    getRedirectTo() {
        return this.options.redirectTo || null;
    }

    canBeRedirectedTo() {
        return this.options.canBeRedirectedTo || true;
    }

    replaceDom(selector, content) {
        this.el.querySelector(selector).innerHTML = content;
    }

    remove() {
        this.el.remove();
    }

    /**
     * The payload that is passed on any panel related events
     * @returns {any}
     */
    eventPayload() {
        return {
            panel: this,
            id: this.id,
            url: this.url,
            options: this.options,
            tags: this.getTags(),
        };
    }
}

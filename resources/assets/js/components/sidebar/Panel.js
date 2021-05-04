export default class {
    constructor(id, url, parent, el, triggerData) {
        this.id = id;
        this.url = url;
        this.parent = parent;
        this.el = el;
        this.triggerData = triggerData;
    }

    show() {
        this.el.style.display = 'block';
    }

    hide() {
        this.el.style.display = 'none';
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
            triggerEl: this.triggerData.el,
            componentKey: this.triggerData.key,
            component: this.triggerData.component,
        };
    }
}

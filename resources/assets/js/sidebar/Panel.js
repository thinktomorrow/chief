export default class {
    constructor(id, url, parent, el) {
        this.id = id;
        this.url = url;
        this.parent = parent;
        this.el = el;
    }

    show() {
        this.el.style.display = "block";
    }

    hide() {
        this.el.style.display = "none";
    }

    replaceComponent(selector, content) {
        this.el.querySelector(selector).innerHTML = content;
    }

    remove() {
        this.el.remove();
    }
}

export default class {
    constructor() {
        this.collection = [];
        this.activePanel = null;
    }

    find(id) {
        return this.collection.find((panel) => panel.id === id);
    }

    findActive() {
        return this.activePanel;
    }

    findFirstSubmitTarget(panel) {
        if (!panel) return null;

        // This is a bit too hardcoded... This makes sure we don't go back to any create pages after submission
        if (!panel.url.includes('/new-fragment') && !panel.url.includes('/create')) {
            return panel;
        }

        return this.findFirstSubmitTarget(panel.parent);
    }

    markAsActive(id) {
        this.activePanel = this.find(id);
    }

    getTags() {
        return [].concat(this.collection.map((panel) => panel.getTags()));
    }

    add(panel) {
        this.collection.push(panel);
    }

    remove(id) {
        const index = this.collection.findIndex((panel) => panel.id === id);

        this.collection[index].remove();
        this.collection.splice(index, 1);
    }

    clear() {
        this.collection.forEach((panel) => panel.remove());

        this.collection = [];
        this.activePanel = null;
    }

    static createId(url) {
        return encodeURIComponent(url);
    }
}

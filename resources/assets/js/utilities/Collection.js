export default class {
    constructor() {
        this.collection = [];
    }

    findIndex(id) {
        return this.collection.findIndex((item) => item.id === id);
    }

    findParentOf(id) {
        const itemIndex = this.findIndex(id);

        // Get previous one
        if (this.collection[itemIndex - 1]) {
            return this.collection[itemIndex - 1];
        }

        // If no one is found, we'll take the last one since this will probably be the parent.
        return this.collection[this.collection.length - 1];
    }

    add(item) {
        this.collection.push(item);
    }

    clear() {
        this.collection = [];

        return this;
    }
}

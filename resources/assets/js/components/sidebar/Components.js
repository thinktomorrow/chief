import Component from './Component';

export default class {
    constructor(components) {
        this.collection = [];
        this.activeComponents = [];

        components.forEach((component) => {
            this.add(component);
        });
    }

    all() {
        return this.collection;
    }

    allIn(container) {
        return this.collection.filter((component) => component.el(container));
    }

    find(key) {
        return this.collection.find((component) => component.key === key);
    }

    allActive() {
        return this.activeComponents;
    }

    addAsActive(key) {
        this.activeComponents.push(this.find(key));
    }

    resetAllActive() {
        this.activeComponents = [];
    }

    add(component) {
        if (!(component instanceof Component)) {
            throw new Error('Components collection should only contain elements of Component type.');
        }

        this.collection.push(component);
    }

    remove(key) {
        const index = this.collection.findIndex((component) => component.key === key);

        this.collection[index].remove();
        this.collection.splice(index, 1);
    }

    clear() {
        this.collection.forEach((component) => component.remove());

        this.collection = [];
        this.activeComponents = [];
    }

    static createId(url) {
        return encodeURIComponent(url);
    }
}

export default class Panels {
    constructor() {
        this.collection = [];
        this.activePanel = null;
    }

    find(id) {
        return this.collection.find((panel) => panel.id === id );
    }

    findActive() {
        return this.activePanel;
    }

    markAsActive(id){
        this.activePanel = this.find(id);
    }

    add(panel) {
        this.collection.push(panel);
    }

    remove(id) {
        const index = this.collection.findIndex((panel) => panel.id === id );
        this.collection.splice(index,1);
    }

    clear() {
        this.collection = [];
        this.activePanel = null;
    }
}

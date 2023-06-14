const HotSpots = function (containerElement, existingHotSpots = []) {
    this.map = new Map();
    this.containerElement = containerElement;
    this.containerWidth = containerElement.getBoundingClientRect().offsetWidth;
    this.containerHeight = containerElement.getBoundingClientRect().offsetHeight;

    existingHotSpots.forEach(function (hotSpot, index) {
        const { x, y } = this.calculateCoordinatesFromTopLeft(hotSpot.top, hotSpot.left);

        this.map.set(index, {
            x,
            y,
            top: hotSpot.top,
            left: hotSpot.left,
            active: false,
        });
    });
};

HotSpots.prototype.listen = function () {
    this.containerElement.addEventListener('click', (event) => {
        const rect = event.target.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
        const index = this.findHotSpotOnCoordinates(x, y);

        if (index !== null) {
            this.activateHotspot(index);
        } else {
            this.addHotspot(x, y);
        }
    });
};

HotSpots.prototype.findHotSpotOnCoordinates = function (x, y) {
    // Radius of clickable area per hotspot
    const radius = 5;

    this.map.forEach((hotspot, index) => {
        if (x >= hotspot.x - radius && x <= hotspot.x + radius &&
            y >= hotspot.y - radius && y <= hotspot.y + radius) {
            return index;
        }
    });

    return null;
};

HotSpots.prototype.addHotspot = function (x, y) {
    const hotspot = {
        x,
        y,
        top: (y / this.containerHeight) * 100,
        left: (x / this.containerWidth) * 100,
        active: true,
    };
    const index = this.map.size;
    this.map.set(index, hotspot);
    this.emitEvent('HotSpotAdded', hotspot);

    this.activateHotspot(index);
};

HotSpots.prototype.activateHotspot = function (index) {
    const hotspot = this.map.get(index);
    if (!hotspot) {
        throw new Error(`No hotspot found on index ${index}`);
    }

    hotspot.active = true;
    this.emitEvent('HotSpotActivated', hotspot);
};

HotSpots.prototype.emitEvent = function (eventName, hotspot) {
    const event = new CustomEvent(eventName, { detail: hotspot });
    document.dispatchEvent(event);
};

HotSpots.prototype.calculateCoordinatesFromTopLeft = function (top, left) {
    return { x: left * this.containerWidth, y: top * this.containerHeight };
};

export default HotSpots;

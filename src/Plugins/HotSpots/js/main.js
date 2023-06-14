import HotSpots from './hotspots';

// Set up hotspots
const hotspots = new HotSpots(
    document.querySelector('#image-overlay-id'),
    window.existingHotSpots || []
);

// Init events
hotspots.listen();

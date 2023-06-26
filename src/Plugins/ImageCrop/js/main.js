import ImageCrop from './imageCrop';

// Set up imageCrop
const imageCrop = new ImageCrop(
    document.querySelector('#image-overlay-id'),
    window.existingImageCrop || []
);

// Init events
imageCrop.listen();

// eslint-disable-next-line object-curly-newline
import { computePosition, flip, shift, offset, autoUpdate } from '@floating-ui/dom';

const dropdown = (config) => ({
    placement: config.placement,
    offset: config.offset,
    init() {
        window.addEventListener('dialog-opened', (e) => {
            if (this.$el.id === e.detail.id) {
                this.create(e.detail.trigger);
            }
        });
    },
    create(reference) {
        // This returns a function which should be invoked when the floating element is removed from the screen.
        autoUpdate(reference, this.$el, () => {
            computePosition(reference, this.$el, {
                placement: this.placement,
                middleware: [
                    offset(this.offset), // Space between referenceEl and floatingEl
                    flip(), // Flip floatingEl to the other side of the referenceEl if it overflows
                    shift({ padding: 8 }), // Shift floatingEl to the side if it overflows
                ],
            }).then(({ x, y }) => {
                Object.assign(this.$el.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                });
            });
        });
    },
});

export { dropdown as default };

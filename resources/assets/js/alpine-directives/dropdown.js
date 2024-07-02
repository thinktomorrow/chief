// eslint-disable-next-line object-curly-newline
import { computePosition, flip, shift, offset, autoUpdate } from '@floating-ui/dom';

const dropdownDirective = (floatingEl, { expression }, { evaluate }) => {
    const { reference, placement } = evaluate(expression);
    const referenceEl = document.querySelector(reference);

    function updatePosition() {
        computePosition(referenceEl, floatingEl, {
            placement,
            middleware: [
                offset(8), // Space between referenceEl and floatingEl
                flip(), // Flip floatingEl to the other side of the referenceEl if it overflows
                shift({ padding: 8 }), // Shift floatingEl to the side if it overflows
            ],
        }).then(({ x, y }) => {
            Object.assign(floatingEl.style, {
                left: `${x}px`,
                top: `${y}px`,
            });
        });
    }

    // This returns a function which should be invoked when the floating element is removed from the screen.
    autoUpdate(referenceEl, floatingEl, updatePosition);
};

export { dropdownDirective as default };

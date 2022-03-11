/**
 * Toggle visibility of element based on a given CSS animation
 * @param {String} containerSelector
 * @param {String} toggleSelector
 */
class AnimatedToggle {
    constructor(container, toggles, options = {}) {
        this.container = container;
        this.toggles = toggles;

        if (!this.container && this.toggles.length === 0) return;

        this.isOpen = false;

        this.onOpen = options.onOpen || function () {};
        this.onClose = options.onClose || function () {};
        this.animationClasses = options.animationClasses || ['animate-slide-in-nav'];

        this._init();
    }

    _init() {
        this.toggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                this._toggle();
            });
        });
    }

    open() {
        this._open();

        this.isOpen = true;
    }

    _toggle() {
        if (this.isOpen) {
            this._close();
        } else {
            this._open();
        }

        this.isOpen = !this.isOpen;
    }

    _open() {
        this.container.classList.remove('hidden');

        this.onOpen();
    }

    _close() {
        this.container.style.animationDirection = 'reverse';

        this.animationClasses.forEach((animationClass) => {
            this.container.classList.remove(animationClass);
        });

        /* eslint-disable */
        void this.container.offsetWidth;
        /* eslint-enable */

        this.animationClasses.forEach((animationClass) => {
            this.container.classList.add(animationClass);
        });

        const onAnimationEnd = () => {
            this.container.classList.add('hidden');
            this.container.style.animationDirection = 'normal';
            this.container.removeEventListener('animationend', onAnimationEnd);

            this.onClose();
        };

        this.container.addEventListener('animationend', onAnimationEnd);
    }
}

const initAnimatedToggle = (
    containerSelector = '[data-mobile-navigation]',
    toggleSelector = '[data-toggle-mobile-navigation]',
    options = {}
) => {
    const container = document.querySelector(containerSelector);
    const toggles = Array.from(document.querySelectorAll(toggleSelector));

    new AnimatedToggle(container, toggles, options);
};

export { initAnimatedToggle as default };

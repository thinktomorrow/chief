export default class {
    constructor(containerEl) {
        if (!containerEl) {
            throw new Error('Sidebar container element does not exist in DOM.');
        }

        this.containerEl = containerEl;
        this.el = this._createDomElement();

        this.sidebarBackdrop = this.el.querySelector('[data-sidebar-backdrop]');
        this.sidebarAside = this.el.querySelector('[data-sidebar-aside]');
        this.sidebarContent = this.el.querySelector('[data-sidebar-content]');
        this.closeButton = this.el.querySelector('[data-sidebar-close-button]');

        this.closeTriggers = Array.from(this.el.querySelectorAll('[data-sidebar-close]'));

        this._closeWithEscape();
    }

    _createDomElement() {
        const template = document.querySelector('#js-sidebar-template');
        const docFragment = document.importNode(template.content, true);
        const el = docFragment.firstElementChild;

        this.containerEl.appendChild(docFragment);

        return el;
    }

    dom() {
        return this.sidebarContent;
    }

    open() {
        this.el.style.display = 'block';
    }

    isOpen() {
        return this.el.style.display === 'block';
    }

    close() {
        Promise.all([
            this._closeElement(this.sidebarBackdrop, 'sidebar-bg-fade-in'),
            this._closeElement(this.sidebarAside, 'sidebar-slide-from-right')
        ]).then(() => {
            this.el.style.display = "none";
        }).catch((error) => {
            console.error(error);
        });
    }

    renderCloseButton() {
        const template = document.querySelector('#js-sidebar-close-button');
        const node = document.importNode(template.content, true);

        this.closeButton.innerHTML = '';
        this.closeButton.appendChild(node);
    }

    _closeWithEscape() {
        window.addEventListener('keydown', (e) => {
            if (this.isOpen() && e.key === 'Escape') {
                this.close();
            }
        });
    }

    _closeElement(element, animationName) {
        return new Promise((resolve, reject) => {
            try {
                element.style.animationDirection = 'reverse';
                element.classList.remove(animationName);
                void element.offsetWidth;
                element.classList.add(animationName);

                const onAnimationEnd = () => {
                    element.style.animationDirection = 'normal';
                    element.removeEventListener('animationend', onAnimationEnd);

                    resolve();
                };

                element.addEventListener('animationend', onAnimationEnd);
            } catch (error) {
                reject(error);
            }
        });
    }
}

export default class Sidebar {
    constructor(el) {

        if (!el) {
            throw new Error('Sidebar element does not exist in DOM.');
        }

        this.el = el;
        this.sidebarBackdrop = this.el.querySelector('[data-sidebar-backdrop]');
        this.sidebarContent = this.el.querySelector('[data-sidebar-content]');
        this.sidebarBackButton = this.el.querySelector('[data-sidebar-back-button]');

        this.backTriggers = Array.from(this.el.querySelectorAll('[data-sidebar-back]'));
    }

    dom() {
        return this.sidebarContent;
    }

    open() {
        this.el.style.display = "block";
    }

    isOpen() {
        return (this.el.style.display === "block");
    }

    close() {
        Promise.all([
            this._closeElement(this.sidebarBackdrop, 'fade-in'),
            this._closeElement(this.sidebarContent, 'slide-from-right')
        ]).then(() => {
            this.el.style.display = "none";
        }).catch((error) => {
            console.error(error);
        });
    }

    setBackButtonDisplay() {
        const template = document.querySelector('#js-sidebar-close-button');

        const node = document.importNode(template.content, true);
        this.sidebarBackButton.innerHTML = '';
        this.sidebarBackButton.appendChild(node);
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
                }

                element.addEventListener('animationend', onAnimationEnd);
            } catch (error) {
                reject(error);
            }
        });
    }
}

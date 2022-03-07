import EventBus from './EventBus';

class CollapsibleNavigation {
    constructor(
        containerSelector = '[data-collapsible-navigation]',
        toggleSelector = '[data-toggle-navigation]',
        expandButtonSelector = '[data-expand-navigation]',
        toggleClassesAttribute = 'data-toggle-classes'
    ) {
        this.containerSelector = containerSelector;
        this.toggleSelector = toggleSelector;
        this.toggleClassesAttribute = toggleClassesAttribute;

        this.container = document.querySelector(containerSelector);
        this.toggleButtons = document.querySelectorAll(toggleSelector);
        this.expandButtons = document.querySelectorAll(expandButtonSelector);
        this.toggleClassesElements = Array.from(document.querySelectorAll(`[${toggleClassesAttribute}]`));

        this.isCollapsed = false;
        this.cookieName = 'is-navigation-collapsed';

        this._init();
    }

    _init() {
        this.isCollapsed = this._getCookieValue();

        this.toggleButtons.forEach((toggleButton) => {
            toggleButton.addEventListener('click', () => {
                if (this.isCollapsed) {
                    this._expand();
                } else {
                    this._collapse();
                }
            });
        });

        this.expandButtons.forEach((expandButton) => {
            expandButton.addEventListener('click', () => {
                this._expand();
            });
        });
    }

    _collapse() {
        EventBus.publish('closeAllDropdowns');

        this.toggleClassesElements.forEach((element) => {
            const classNames = element.getAttribute(this.toggleClassesAttribute).split(' ');

            classNames.forEach((className) => {
                if (className === '') return;
                element.classList.add(className);
            });
        });

        this.isCollapsed = true;

        this._setCookie();
    }

    _expand() {
        this.toggleClassesElements.forEach((element) => {
            const classNames = element.getAttribute(this.toggleClassesAttribute).split(' ');

            classNames.forEach((className) => {
                if (className === '') return;
                element.classList.remove(className);
            });
        });

        this.isCollapsed = false;

        this._setCookie();
    }

    _getCookieValue() {
        const cookieArr = document.cookie.split(';');

        for (let i = 0; i < cookieArr.length; i++) {
            const cookiePair = cookieArr[i].split('=');

            if (this.cookieName === cookiePair[0].trim()) {
                return JSON.parse(decodeURIComponent(cookiePair[1]));
            }
        }

        return null;
    }

    _setCookie(days = 365) {
        const expires = new Date();
        expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);

        const cookieString = `${this.cookieName}=${this.isCollapsed}; expires=${expires.toUTCString()}; path=${
            this.path
        };`;

        document.cookie = cookieString.trim(' ');
    }
}

const initCollapsibleNavigation = () => {
    new CollapsibleNavigation();
};

export { initCollapsibleNavigation as default };

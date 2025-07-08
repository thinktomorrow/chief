const Dialog = (config) => ({
    isOpen: config.isOpen,
    wired: config.wired,
    init() {
        window.addEventListener('open-dialog', (e) => {
            const firstChild = this.$el.firstElementChild;

            if (!firstChild) {
                throw new Error('Dialog component should be wired or must have a child element with an id attribute.');
            }

            if (firstChild.id === e.detail.id) {
                this.open();

                this.$dispatch('dialog-opened', {
                    id: e.detail.id,
                    el: this.$el,
                    trigger: e.target,
                });
            }
        });

        window.addEventListener('close-dialog', (e) => {
            const firstChild = this.$el.firstElementChild;

            if (!firstChild) {
                throw new Error('Dialog component should be wired or must have a child element with an id attribute.');
            }

            if (firstChild.id === e.detail.id) {
                this.close();

                this.$dispatch('dialog-closed', {
                    id: e.detail.id,
                    el: this.$el,
                    trigger: e.target,
                });
            }
        });
    },
    open() {
        this.isOpen = true;
    },
    close() {
        if (this.wired) {
            this.$wire.close();
        } else {
            this.isOpen = false;
        }
    },
});

export { Dialog as default };

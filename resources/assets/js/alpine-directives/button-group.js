const ButtonGroup = () => ({
    init() {
        this.repositionTabMarker();

        this.onVisible(this.$root, () => {
            this.$nextTick(() => {
                this.repositionTabMarker();
            });
        });
    },
    repositionTabMarker() {
        this.$nextTick(() => {
            const tabElement = Array.from(this.$refs.buttons.children).find(
                (tab) => tab.getAttribute('aria-selected') === 'true'
            );

            if (!tabElement) return;

            this.$refs.tabMarker.style.width = `${tabElement.offsetWidth}px`;
            this.$refs.tabMarker.style.left = `${tabElement.offsetLeft}px`;
        });
    },
    // Src: https://stackoverflow.com/questions/1462138/event-listener-for-when-element-becomes-visible#answer-66394121
    onVisible(element, callback) {
        new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.intersectionRatio > 0) {
                    callback(element);
                    observer.disconnect();
                }
            });
        }).observe(element);
    },
});

export { ButtonGroup as default };

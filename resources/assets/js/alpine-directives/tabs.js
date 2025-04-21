const Tabs = (config) => ({
    activeTab: config.activeTab,
    dispatchTab: config.dispatchTab,
    shouldListenForExternalTab: config.shouldListenForExternalTab,
    reference: config.reference,
    showNav: config.showNav,
    showTabs: config.showTabs,
    tabs: [],
    init() {
        this.tabs = Array.from(this.$refs.tabs.children).map((node) => ({
            id: node.getAttribute('data-tab-id'),
            label: JSON.parse(node.getAttribute('data-tab-label')),
        }));

        if (!this.activeTab) {
            this.activeTab = this.tabs.length > 0 ? this.tabs[0].id : null;
        }

        this.repositionTabMarker();

        if (this.shouldListenForExternalTab) {
            window.addEventListener('chieftab', (e) => {
                this.listenForExternalTab(e);
            });
        }

        this.onVisible(this.$root, () => {
            this.$nextTick(() => {
                this.repositionTabMarker();
            });
        });
    },
    listenForExternalTab(e) {
        if (!this.shouldListenForExternalTab) return;

        if (this.activeTab === e.detail.id) return;

        // Check if this tabs accepts the given external tab
        this.tabs.forEach(({ id }) => {
            if (id === e.detail.id) {
                this.activeTab = e.detail.id;
            }
        });

        this.repositionTabMarker();
    },
    showTab(id) {
        this.activeTab = id;

        if (!this.dispatchTab) return;

        this.$dispatch('chieftab', { id, reference: this.reference });

        this.repositionTabMarker();
    },
    repositionTabMarker() {
        this.$nextTick(() => {
            const tabElement = Array.from(this.$root.querySelectorAll('[role="tablist"] [role="tab"]')).find(
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

export { Tabs as default };

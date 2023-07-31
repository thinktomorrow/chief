export default () => ({
    activeTab: null,

    items: async (tabsId) => {
        const { children } = document.getElementById(tabsId);

        return Array.from(children).map((child) => ({
            id: child.getAttribute('data-tab-id'),
            name: child.getAttribute('data-tab-name'),
        }));

        // return Array.from(children).map((child) => {
        //     console.log(`ID: ${child.getAttribute('id')}`);
        //     // name: 'test', id: 0,
        // });

        // return [
        //     { name: 'test', id: 0 },
        //     { name: 'test 2', id: 1 },
        // ];
    },

    // Option to hide the navigation and only show the tabs
    hideNav: false,

    // nav: (tab, index) => ({
    //     'x-on:click': function (e) {
    //         console.log(index);
    //         // this.open = !this.open;
    //         console.log(e.currentTarget);
    //     },
    //     // 'x-bind:id': function () {
    //     //     return tab.id;
    //     // },
    //     // 'x-html': function () {
    //     //     console.log(tab);
    //     //     return tab.name;
    //     // },
    // }),

    tab: (id) => ({
        'x-show': function () {
            return this.activeTab === id;
        },
    }),
});

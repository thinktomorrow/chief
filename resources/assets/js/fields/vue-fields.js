/**
 * Create vue instances for specific elements.
 * Useful for async loading of vue components.
 *
 * @param container
 * @param selector
 */
const vueFields = function (container, selector = '[data-vue-fields]') {
    Array.from(container.querySelectorAll(selector)).forEach((el) => {
        // Add an id for vue because this is required
        if (!el.hasAttribute('id')) {
            el.setAttribute('id', `vue_${Math.random().toString(16).substr(2, 8)}`);
        }

        console.log(el, el.id);

        const res = window.Vue.compile(el.outerHTML);

        new window.Vue({
            render: res.render,
            staticRenderFns: res.staticRenderFns,
        }).$mount('#' + el.getAttribute('id')); // eslint-disable-line
    });
};

export { vueFields as default };

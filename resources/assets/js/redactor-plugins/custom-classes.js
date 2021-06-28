(function ($R) {
    $R.add('plugin', 'custom-classes', {
        init(app) {
            this.app = app;
            this.toolbar = app.toolbar;
            this.opts = app.opts;
            this.selection = app.selection;
        },
        /** Called when redactor instance is enabled */
        start() {
            const dropdown = {};
            for (const key in this.opts.customClasses) {
                const custom_tag = this.opts.customClasses[key];

                dropdown[key] = {
                    title: custom_tag.title,
                    api: 'plugin.custom-classes.toggle',
                    args: custom_tag,
                };
            }

            const $button = this.toolbar.addButtonAfter('link', 'toggle-button', { title: 'Wijzig link opmaak' });

            $button.setIcon('<span><svg width="18" height="18"><use xlink:href="#button"/></svg></span>');
            $button.setDropdown(dropdown);
        },
        toggle(custom_tag) {
            const currentEl = this.selection.getParent();

            // If nothing is selected or the current element does not match our tag whitelist, we abort
            if (!currentEl || custom_tag.tags.indexOf(currentEl.tagName.toLowerCase()) == -1) {
                return;
            }

            // Remove existing classes first
            const current_classes = currentEl.classList;
            while (current_classes.length > 0) {
                current_classes.remove(current_classes.item(0));
            }

            // Add our requested classes
            const classes = custom_tag.class.split(' ');
            for (const i in classes) {
                // Avoid empty execution
                if (!classes[i]) continue;

                currentEl.classList.add(classes[i]);
            }
        },
    });
})(Redactor);

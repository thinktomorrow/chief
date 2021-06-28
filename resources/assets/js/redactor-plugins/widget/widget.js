(function ($R) {
    $R.add('plugin', 'widget', {
        translations: {
            en: {
                widget: 'Widget',
                'widget-html-code': 'Widget HTML Code',
            },
        },
        modals: {
            widget:
                '<form action=""> \
                    <div class="form-item"> \
                        <label for="modal-widget-input">## widget-html-code ## <span class="req">*</span></label> \
                        <textarea id="modal-widget-input" name="widget" style="height: 200px;"></textarea> \
                    </div> \
                </form>',
        },
        init(app) {
            this.app = app;
            this.lang = app.lang;
            this.opts = app.opts;
            this.toolbar = app.toolbar;
            this.component = app.component;
            this.insertion = app.insertion;
            this.inspector = app.inspector;
            this.selection = app.selection;
        },
        // messages
        onmodal: {
            widget: {
                opened($modal, $form) {
                    $form.getField('widget').focus();

                    if (this.$currentItem) {
                        const code = decodeURI(this.$currentItem.attr('data-widget-code'));
                        $form.getField('widget').val(code);
                    }
                },
                insert($modal, $form) {
                    const data = $form.getData();
                    this._insert(data);
                },
            },
        },
        oncontextbar(e, contextbar) {
            const data = this.inspector.parse(e.target);
            if (!data.isFigcaption() && data.isComponentType('widget')) {
                const node = data.getComponent();
                const buttons = {
                    edit: {
                        title: this.lang.get('edit'),
                        api: 'plugin.widget.open',
                        args: node,
                    },
                    remove: {
                        title: this.lang.get('delete'),
                        api: 'plugin.widget.remove',
                        args: node,
                    },
                };

                contextbar.set(e, node, buttons, 'bottom');
            }
        },
        onbutton: {
            widget: {
                observe(button) {
                    this._observeButton(button);
                },
            },
        },

        // public
        start() {
            const obj = {
                title: this.lang.get('widget'),
                api: 'plugin.widget.open',
                observe: 'widget',
            };

            const $button = this.toolbar.addButton('widget', obj);
            $button.setIcon('<i class="re-icon-widget"></i>');
        },
        open() {
            this.$currentItem = this._getCurrent();

            const options = {
                title: this.lang.get('widget'),
                width: '600px',
                name: 'widget',
                handle: 'insert',
                commands: {
                    insert: { title: this.$currentItem ? this.lang.get('save') : this.lang.get('insert') },
                    cancel: { title: this.lang.get('cancel') },
                },
            };

            this.app.api('module.modal.build', options);
        },
        remove(node) {
            this.component.remove(node);
        },

        // private
        _getCurrent() {
            const current = this.selection.getCurrent();
            const data = this.inspector.parse(current);
            if (data.isComponentType('widget')) {
                return this.component.build(data.getComponent());
            }
        },
        _insert(data) {
            this.app.api('module.modal.close');

            if (data.widget.trim() === '') {
                return;
            }

            const html = this._isHtmlString(data.widget) ? data.widget : document.createTextNode(data.widget);
            const $component = this.component.create('widget', html);
            $component.attr('data-widget-code', encodeURI(data.widget.trim()));
            this.insertion.insertHtml($component);
        },
        _isHtmlString(html) {
            return !(typeof html === 'string' && !/^\s*<(\w+|!)[^>]*>/.test(html));
        },
        _observeButton(button) {
            const current = this.selection.getCurrent();
            const data = this.inspector.parse(current);

            if (data.isComponentType('table')) button.disable();
            else button.enable();
        },
    });
})(Redactor);
(function ($R) {
    $R.add('class', 'widget.component', {
        mixins: ['dom', 'component'],
        init(app, el) {
            this.app = app;

            // init
            return el && el.cmnt !== undefined ? el : this._init(el);
        },
        getData() {
            return {
                html: this._getHtml(),
            };
        },

        // private
        _init(el) {
            if (typeof el !== 'undefined') {
                const $node = $R.dom(el);
                const $figure = $node.closest('figure');
                if ($figure.length !== 0) {
                    this.parse($figure);
                } else {
                    this.parse('<figure>');
                    this.html(el);
                }
            } else {
                this.parse('<figure>');
            }

            this._initWrapper();
        },
        _getHtml() {
            const $wrapper = $R.dom('<div>');
            $wrapper.html(this.html());
            $wrapper.find('.redactor-component-caret').remove();

            return $wrapper.html();
        },
        _initWrapper() {
            this.addClass('redactor-component');
            this.attr({
                'data-redactor-type': 'widget',
                tabindex: '-1',
                contenteditable: false,
            });
        },
    });
})(Redactor);

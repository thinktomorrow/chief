(function ($R) {
    $R.add('plugin', 'rich-links', {
        init(app) {
            this.app = app;
            this.opts = app.opts;
            this.component = app.component;

            this.links = [];
        },
        // messages
        onmodal: {
            link: {
                open($modal, $form) {
                    if (!this.opts.definedlinks) return;

                    this.$modal = $modal;
                    this.$form = $form;

                    this._load();
                },
            },
        },

        // private
        _load() {
            if (typeof this.opts.definedlinks === 'object') {
                this._build(this.opts.definedlinks);
            } else {
                let $url = this.opts.definedlinks;
                if (this.opts.locale) {
                    $url += `?locale=${this.opts.locale}`;
                }
                $R.ajax.get({
                    url: $url,
                    success: this._build.bind(this),
                });
            }
        },
        _build(data) {
            var $selector = this.$modal.find('#redactor-defined-links');
            if ($selector.length === 0) {
                const $body = this.$modal.getBody();
                const $item = $R.dom('<div class="form-item" />');
                var $selector = $R.dom('<select id="redactor-defined-links" />');

                $item.append($selector);
                $body.prepend($item);
            }

            this.links = [];

            $selector.html('');
            $selector.off('change');

            for (const key in data) {
                if (!data.hasOwnProperty(key) || typeof data[key] !== 'object') {
                    continue;
                }

                this.links[key] = data[key];

                const $option = $R.dom('<option>');
                $option.val(key);
                $option.html(data[key].name);

                $selector.append($option);
            }

            $selector.on('change', this._select.bind(this));
        },
        _select(e) {
            const formData = this.$form.getData();
            const key = $R.dom(e.target).val();
            let data = { text: '', url: '' };

            if (key !== '0') {
                data.text = this.links[key].name;
                data.url = this.links[key].url;
            }

            if (formData.text !== '') {
                data = { url: data.url, text: data.text };
            }

            this.$form.setData(data);
        },
    });
})(Redactor);

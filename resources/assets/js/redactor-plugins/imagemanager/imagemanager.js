(function ($R) {
    $R.add('plugin', 'imagemanager', {
        translations: {
            en: {
                choose: 'Choose',
            },
        },
        init(app) {
            this.app = app;
            this.lang = app.lang;
            this.opts = app.opts;
        },
        // messages
        onmodal: {
            image: {
                open($modal, $form) {
                    if (!this.opts.imageManagerJson) return;
                    this._load($modal);
                },
            },
        },

        // private
        _load($modal) {
            const $body = $modal.getBody();

            this.$box = $R.dom('<div>');
            this.$box.attr('data-title', this.lang.get('choose'));
            this.$box.addClass('redactor-modal-tab');
            this.$box.hide();
            this.$box.css({
                overflow: 'auto',
                height: '300px',
                'line-height': 1,
            });

            $body.append(this.$box);

            $R.ajax.get({
                url: this.opts.imageManagerJson,
                success: this._parse.bind(this),
            });
        },
        _parse(data) {
            for (const key in data) {
                const obj = data[key];
                if (typeof obj !== 'object') continue;

                const $img = $R.dom('<img>');
                const url = obj.thumb ? obj.thumb : obj.url;

                $img.attr('src', url);
                $img.attr('data-params', encodeURI(JSON.stringify(obj)));
                $img.css({
                    width: '96px',
                    height: '72px',
                    margin: '0 4px 2px 0',
                    cursor: 'pointer',
                });

                $img.on('click', this._insert.bind(this));

                this.$box.append($img);
            }
        },
        _insert(e) {
            e.preventDefault();

            const $el = $R.dom(e.target);
            const data = JSON.parse(decodeURI($el.attr('data-params')));

            this.app.api('module.image.insert', { image: data });
        },
    });
})(Redactor);

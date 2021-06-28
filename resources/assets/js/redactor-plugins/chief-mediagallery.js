(function ($R) {
    $R.add('plugin', 'chief-mediagallery', {
        translations: {
            en: {
                choose: 'Gallery',
            },
            nl: {
                choose: 'Galerij',
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
                    if (!this.opts.mediagalleryApi) return;

                    this._load($modal);
                },
            },
        },

        // private
        _load($modal) {
            const $body = $modal.getBody();

            const $tab = $R.dom('<div>');
            $tab.attr('data-title', this.lang.get('choose'));
            $tab.addClass('redactor-modal-tab');
            $tab.hide();

            $wrapper = $R.dom('<div>');
            $wrapper.css({
                'overflow-y': 'scroll',
                height: '340px',
                'line-height': 1,
            });

            $tab.append($wrapper);
            $body.append($tab);

            this.$box = $R.dom('<div>');
            this.$box.addClass('row');

            $wrapper.append(this.$box);
            $tab.append(
                $R
                    .dom('<div>')
                    .addClass('btn btn-primary mt-3')
                    .append('Laad meer afbeeldingen')
                    .on('click', this.loadMore.bind(this))
            );

            $R.ajax.get({
                url: this.opts.mediagalleryApi,
                success: this._parse.bind(this),
            });
        },
        _parse(data) {
            for (const key in data) {
                const obj = data[key];
                if (typeof obj !== 'object') continue;

                const $div = $R.dom('<div>');
                $div.addClass('column-3 border rounded border-transparent hover:border-grey-100 hover:bg-grey-50 p-2');

                const $imgContainer = $R.dom('<div>');
                $imgContainer.addClass('flex items-center justify-center');
                $imgContainer.css({
                    width: '100%',
                    height: '75px',
                    margin: '0 0 10px 0',
                    cursor: 'pointer',
                    overflow: 'hidden',
                });

                const $img = $R.dom('<img>');
                const url = obj.thumb ? obj.thumb : obj.url;
                $img.attr('src', url);
                $img.attr('data-params', encodeURI(JSON.stringify(obj)));
                $img.css({
                    cursor: 'pointer',
                });

                $img.on('click', this._insert.bind(this));

                $div.append($imgContainer);
                $imgContainer.append($img);
                $div.append(
                    $R
                        .dom('<p>')
                        .css({
                            margin: '0 0 6px 0',
                            wordBreak: 'break-word',
                        })
                        .append(obj.filename)
                );
                $div.append($R.dom('<strong>').append(obj.size));

                this.$box.append($div);
            }
        },
        _insert(e) {
            e.preventDefault();

            const $el = $R.dom(e.target);
            const data = JSON.parse(decodeURI($el.attr('data-params')));

            this.app.api('module.image.insert', { image: data });
        },
        loadMore() {
            $R.ajax.get({
                url: `${this.opts.mediagalleryApi}?offset=${this.$box.children().length}`,
                success: this._parse.bind(this),
            });
        },
    });
})(Redactor);

(function ($R) {
    $R.add('plugin', 'clips', {
        translations: {
            en: {
                clips: 'Clips',
                'clips-select': 'Please, select a clip',
            },
        },
        modals: {
            clips: '',
        },
        init(app) {
            this.app = app;
            this.opts = app.opts;
            this.lang = app.lang;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
        },
        // messages
        onmodal: {
            clips: {
                open($modal) {
                    this._build($modal);
                },
            },
        },

        // public
        start() {
            if (!this.opts.clips) return;

            const data = {
                title: this.lang.get('clips'),
                api: 'plugin.clips.open',
            };

            const $button = this.toolbar.addButton('clips', data);
            $button.setIcon('<i class="re-icon-clips"></i>');
        },
        open(type) {
            const options = {
                title: this.lang.get('clips'),
                width: '600px',
                name: 'clips',
            };

            this.app.api('module.modal.build', options);
        },

        // private
        _build($modal) {
            const $body = $modal.getBody();
            const $label = this._buildLabel();
            const $list = this._buildList();

            this._buildItems($list);

            $body.html('');
            $body.append($label);
            $body.append($list);
        },
        _buildLabel() {
            const $label = $R.dom('<label>');
            $label.html(this.lang.parse('## clips-select ##:'));

            return $label;
        },
        _buildList() {
            const $list = $R.dom('<ul>');
            $list.addClass('redactor-clips-list');

            return $list;
        },
        _buildItems($list) {
            const items = this.opts.clips;
            for (let i = 0; i < items.length; i++) {
                const $li = $R.dom('<li>');
                const $item = $R.dom('<span>');

                $item.attr('data-index', i);
                $item.html(items[i][0]);
                $item.on('click', this._insert.bind(this));

                $li.append($item);
                $list.append($li);
            }
        },
        _insert(e) {
            const $item = $R.dom(e.target);
            const index = $item.attr('data-index');
            const html = this.opts.clips[index][1];

            this.app.api('module.modal.close');
            this.insertion.insertRaw(html);
        },
    });
})(Redactor);

(function($R)
{
    $R.add('plugin', 'snippets', {
        init: function(app)
        {
            this.app = app;
            this.opts = app.opts;
            this.toolbar = app.toolbar;
        },
        modals: {
            'snippets':
                '<form action=""> \
                    <div class="form-item"> \
                        <label for="modal-video-input">Snippets <span class="req">*</span></label> \
                        <textarea id="modal-video-input" name="video" style="height: 160px;"></textarea> \
                    </div> \
                </form>'
        },
        // messages
        onmodal: {
            snippets: {
                open: function($modal, $form)
                {
                    console.log('open trigger');
                    if (!this.opts.snippetslink) return;

                    this.$modal = $modal;
                    this.$form = $form;

                    this._load($modal);
                }
            }
        },
        start: function()
        {
            var obj = {
                title: 'snippets',
                api: 'plugin.snippets.open'
            };

            var $button = this.toolbar.addButtonAfter('image', 'snippets', obj);
            $button.setIcon('<i class="re-icon-video"></i>');
        },
        _load: function($modal)
        {
            console.log('load');
            var $body = $modal.getBody();

			this.$box = $R.dom('<div>');
			this.$box.attr('data-title', 'choose');
			this.$box.addClass('redactor-modal-tab');
			this.$box.hide();
			this.$box.css({
    			overflow: 'auto',
    			height: '300px',
    			'line-height': 1
			});

			$body.append(this.$box);
            $R.ajax.get({
                url: this.opts.snippetslink,
                success: this.data.bind(this)
            });
        },
        _build: function(data)
        {
            var $selector = this.$modal.find('#redactor-defined-links');
            if ($selector.length === 0)
            {
                var $body = this.$modal.getBody();
                var $item = $R.dom('<div class="form-item" />');
                var $selector = $R.dom('<select id="redactor-defined-links" />');

                $item.append($selector);
                $body.prepend($item);
            }

            this.links = [];

            $selector.html('');
            $selector.off('change');

            for (var key in data)
            {
                if (!data.hasOwnProperty(key) || typeof data[key] !== 'object')
                {
                    continue;
                }

                this.links[key] = data[key];

                var $option = $R.dom('<option>');
                $option.val(key);
                $option.html(data[key].name);

                $selector.append($option);
            }

            $selector.on('change', this._select.bind(this));
        },
        open: function()
		{
            console.log('test open')
            var options = {
                title: 'snippets',
                width: '600px',
                name: 'snippets',
                handle: 'insert',
                commands: {
                    insert: { title: 'insert' },
                    cancel: { title: 'cancel' }
                }
            };
            
            this.app.api('module.modal.build', options);
        },
    });
})(Redactor);
;(function($R){

    $R.add('plugin', 'redactorColumns', {
        init: function(app){
            this.app = app;
            this.toolbar = app.toolbar;
            this.block = app.block;
        },

        /** Called when redactor instance is enabled */
        start: function(){

            console.log();

            /**
             * For support of columns in the wysiwyg, we'll need to make sure that only the
             * column body is editable. Nice effect of contenteditable is that the hard
             * enter is treated as soft enter inside the column as well as that the
             * tab brings the cursor to the next column in line.
             */
            this.restrictColumnEditability();

            var dropdown = {};
            var $button = this.toolbar.addButton('column', { title: 'Columns' });

            dropdown.two = { title: '2 columns', api: 'plugin.redactorColumns.set', args: [6,6] };
            dropdown.three = { title: '<strong>3</strong> columns', api: 'plugin.redactorColumns.set', args: [4,4,4] };

            $button.setIcon('<i class="icon icon-grid"></i>');
            $button.setDropdown(dropdown);
        },

        set: function(sections){

            var columns = [];

            sections.forEach(function(section){
                columns.push('<div class="column-'+ section +'" contenteditable="true"></div>');
            });

            this.app.insertion.insertHtml('<br><div class="row gutter" contenteditable="false">' + columns.join('') + '</div><br>');
        },

        restrictColumnEditability: function()
        {
            var html = this.app.source.getCode();

            html = html.replace('<div class="row', '<div contenteditable="false" class="row');
            html = html.replace('<div class="column', '<div contenteditable="true" class="column');

            this.app.source.setCode(html);
        },
    });

})(Redactor);
;(function($R){

    $R.add('plugin', 'redactorColumns', {
        init: function(app){
            this.app = app;
            this.toolbar = app.toolbar;
            this.block = app.block;
        },

        /** Called when redactor instance is enabled */
        start: function(){

            var dropdown = {};
            var $button = this.toolbar.addButton('column', { title: 'Columns' });

            dropdown.two = { title: '2 columns', api: 'plugin.redactorColumns.setAsTable', args: [6,6] };
            dropdown.three = { title: '3 columns', api: 'plugin.redactorColumns.setAsTable', args: [4,4,4] };

            $button.setIcon('<i class="icon icon-grid"></i>');
            $button.setDropdown(dropdown);
        },

        setAsTable: function(sections){

            var columns = [];

            sections.forEach(function(section){
                columns.push('<td class="column-'+ section +'"></td>');
            });

            this.app.insertion.insertHtml('<table class="block"><tbody class="block"><tr class="row gutter">' + columns.join('') + '</tr></tbody></table>');
        },
    });

})(Redactor);
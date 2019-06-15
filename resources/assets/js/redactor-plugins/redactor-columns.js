;(function($R){

    $R.add('plugin', 'redactorColumns', {
        translations: {
    		en: {
    			"columns": "Columns",
    			"columns-2": "2 columns",
    			"columns-3": "2 columns",
            },
            nl: {
                "columns": "Kolommen",
    			"columns-2": "2 kolommen",
    			"columns-3": "2 kolommen",
            }
        },
        init: function(app){
            this.app = app;
            this.toolbar = app.toolbar;
            this.block = app.block;
        },

        /** Called when redactor instance is enabled */
        start: function(){

            var dropdown = {};
            var $button = this.toolbar.addButton('column', { title: this.lang.get('columns') });

            dropdown.two = { title: this.lang.get('columns-2'), api: 'plugin.redactorColumns.setAsTable', args: [6,6] };
            dropdown.three = { title: this.lang.get('columns-3'), api: 'plugin.redactorColumns.setAsTable', args: [4,4,4] };

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

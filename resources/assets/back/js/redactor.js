;(function($){

    $('.redactor-editor').redactor({
        focus:false,
        pastePlainText:true,
        buttons: ['html','formatting','bold','italic',
            'unorderedlist','orderedlist','outdent','indent',
            'link','alignment']
    });

})(jQuery);
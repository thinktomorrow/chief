        </div>
    </article>
</main>

<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>


<script src="/chief-assets/back/js/main.js"></script>
<script>

    /**
     * Global Eventbus which allows to emit and listen to
     * events coming from components
     */
    window.Eventbus = new Vue();

    /**
     * Application vue instance. We register the vue instance after our custom
     * scripts so vue components are loaded properly before the main Vue.
     */
    window.App = new Vue({
        el: "#main",
        data: {
            errors: new Errors(),
        },
        methods:{
            showModal: function(id, options){
                return window.showModal(id, options);
            },
            closeModal: function(id){
                return window.closeModal(id);
            },
            selectTab: function(hash){
                Eventbus.$emit('select-tab',hash);
            },
            clear: function(field){
                Eventbus.$emit('clearErrors', field)
            }
        },
    });

    window.showModal = function(id, options){
        Eventbus.$emit('open-modal',id, options);
    };

    window.closeModal = function(id){
        Eventbus.$emit('close-modal',id);
    };

    /** Tippy tooltip init */
    window.tippy('[title]', {
        arrow: true,
        animation: 'shift-toward'
    });

</script>

<script>
$(function() {
    // SCROLL TO ID
    $('a[href*=""]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 50,
                }, 2000);
                return false;
            }
        }
    });

    // Clone your html into a pre box
    // !important: You can't skip a number because the loop stops working.
    // and all the id's needs to be in the dom to be recognized by the function
    function cloneHtml(){
        $('[id^=clone-]').each(function (i) {
            var cloneDiv = $('#clone-'+ i).html();
            var stripFromTag = cloneDiv.replace(/\/</,'&lt;',/\/>/,'&gt;');
            $('#code-'+ i).text(cloneDiv);

        });
    }
    cloneHtml();

    // BRING IN THE COLORS

    // Function to turn rgb to hex
    function rgb2hex(orig){
        var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
        return (rgb && rgb.length === 4) ? "#" +
        ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
    };



    var colorArray=[];

    $('.color-block > [class^=bg-]').each(function (i,j) {
        var rgb = $(this).css('background-color');
        var hex = rgb2hex($(this).css('background-color'));

        $('.rgbCode-'+i).text( rgb );
        $('.hexCode-'+i).text( hex );

    });

    // TABS
    $('#example .panel-tabs li').click(function(){
        var nav_tabs = $('#example .panel-tabs li');
        var panel_tabs = $('.tab');
        var tab_id = $(this).attr('data-tab');

        nav_tabs.removeClass('active');
        panel_tabs.removeClass('active');


        $(this).addClass('active');
        $("#"+tab_id).toggleClass('active');
    })

});
</script>

</body>
</html>
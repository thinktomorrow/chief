<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="google" content="notranslate" />
    <meta http-equiv="Content-Language" content="nl_BE" />
    <title>Chief • @yield('page-title', 'Admin')</title>
    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/back/img/favicon.ico')}}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- hide vue elements until vue is loaded -->
    <style type="text/css">[v-cloak]{ display:none; }</style>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">

    <!--Load redactor -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/back/css/vendors/redactor.css') }}" />
    <script src="{{ asset('assets/back/js/vendors/redactor.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ cached_asset('/assets/back/css/main.css','back') }}">
    @stack('custom-styles')
</head>

<body>
@include('back._layouts._nav.nav')

<main id="main">
    @yield('header')

    <section id="content" class="container">
        @include('back._elements.messages')
        @yield('content')
    </section>

    @include('back._modules.footer')
    @stack('sidebar')
    @stack('custom-components')

</main>

<script src="{{ cached_asset('/assets/back/js/main.js','back') }}"></script>
@stack('custom-scripts')

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
        created: function(){
            this.errors.record({!! isset($errors) ? json_encode($errors->getMessages()) : json_encode([]) !!});

            Eventbus.$on('clearErrors', (field) => {
                this.errors.clear(field);
            });
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

@stack('custom-scripts-after-vue')

</body>
</html>

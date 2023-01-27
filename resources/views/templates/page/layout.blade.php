@props([
    'title' => null
])

<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<!-- This project was proudly build by Think Tomorrow. More info at https://thinktomorrow.be -->
<head>
    <script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

    @include('chief::templates.page._partials.metatags', ['title' => $title])
    @include('chief::templates.page._partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Hide Vue elements until Vue is fully loaded --}}
    <style type="text/css">
        .v-loading { display: none !important; }
        [v-cloak].v-loading { display: block !important; }
        [v-cloak] { display: none !important; }
    </style>

    <link rel="stylesheet" href="{{ chief_cached_asset('chief-assets/back/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back/css/vendor/slim.min.css') }}">

    @stack('custom-styles')
</head>
<body class="min-h-screen bg-grey-100">
    <main id="main">
        {{ $slot }}

        @stack('portals')
    </main>

    @include('chief::templates.page._partials.symbols')

    <script src="{{ chief_cached_asset('chief-assets/back/js/main.js') }}"></script>
    <script src="{{ asset('assets/back/js/vendor/slim.min.js') }}"></script>

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

                Eventbus.$on('clearErrors', (field) => { this.errors.clear(field); });
                Eventbus.$on('enable-update-form', this.enableUpdateForm);
                Eventbus.$on('disable-update-form', this.disableUpdateForm);
            },
            methods:{
                showModal: function(id, options){
                    return window.showModal(id, options);
                },
                closeModal: function(id){
                    return window.closeModal(id);
                },
                closeDropdown: function(id){
                    return window.closeDropdown(id);
                },
                selectTab: function(hash){
                    Eventbus.$emit('select-tab',hash);
                },
                clear: function(field){
                    Eventbus.$emit('clearErrors', field)
                },
                generateSitemap: function(id, options){
                    Eventbus.$emit('open-modal', id, options);
                    axios.post('{{route('chief.back.sitemap.generate')}}', {
                        _method: 'POST'
                    }).then((response) => {
                        Eventbus.$emit('close-modal',id);
                    }).catch((errors) => {
                        alert('error');
                    })
                },
                duplicateImageComponent: function(options){
                    Eventbus.$emit('duplicate-image-component', options);
                },
                enableUpdateForm: () => {
                    let saveButtons = document.querySelectorAll('[data-submit-form="updateForm"]');
                    saveButtons.forEach((button) => {
                        button.disabled = false;
                        button.style.filter = 'none';
                    })
                },
                disableUpdateForm: () => {
                    let saveButtons = document.querySelectorAll('[data-submit-form="updateForm"]');
                    saveButtons.forEach((button) => {
                        button.disabled = true;
                        button.style.filter = 'grayscale(100)';
                    });
                }
            },
        });

        window.showModal = function(id, options){
            Eventbus.$emit('open-modal', id, options);
        };

        Vue.prototype.showModal = window.showModal;

        window.closeModal = function(id){
            Eventbus.$emit('close-modal',id);
        };

        // Close dropdown outside of the dropdown - used by backdrop
        window.closeDropdown = function(id){
            Eventbus.$emit('close-dropdown',id);
        };
    </script>

    <script src="{{ chief_cached_asset('chief-assets/back/js/native.js') }}"></script>

    @stack('custom-scripts-after-vue')
</body>
</html>

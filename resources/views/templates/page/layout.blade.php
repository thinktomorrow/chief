@php use Thinktomorrow\Chief\Plugins\ChiefPluginSections; @endphp
@props([
    'title' => null
])
    <!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<!-- This project was proudly build by Think Tomorrow. More info at https://thinktomorrow.be -->
<head>
    <script>(function (html) {
            html.className = html.className.replace(/\bno-js\b/, 'js')
        })(document.documentElement);</script>

    @include('chief::templates.page._partials.metatags', ['title' => $title])
    @include('chief::templates.page._partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    {{-- Hide Vue elements until Vue is fully loaded --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        /*.v-loading {*/
        /*    display: none !important;*/
        /*}*/

        /*[v-cloak].v-loading {*/
        /*    display: block !important;*/
        /*}*/

        /*[v-cloak] {*/
        /*    display: none !important;*/
        /*}*/
    </style>

    <link rel="stylesheet" href="{{ chief_cached_asset('chief-assets/back/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/back/css/vendor/slim.min.css') }}">

    <livewire:styles/>

    @stack('custom-styles')
</head>
<body class="min-h-screen bg-grey-100">
    <main id="main">
        {{ $slot }}

        @stack('portals')
    </main>

    @include('chief::templates.page._partials.symbols')
    @include('chief::templates.page._partials.refresh-modal')

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
            created: function () {
                this.errors.record({!! isset($errors) ? json_encode($errors->getMessages()) : json_encode([]) !!});

                Eventbus.$on('clearErrors', (field) => {
                    this.errors.clear(field);
                });
                Eventbus.$on('enable-update-form', this.enableUpdateForm);
                Eventbus.$on('disable-update-form', this.disableUpdateForm);
            },
            methods: {
                closeDropdown: function (id) {
                    return window.closeDropdown(id);
                },
                selectTab: function (hash) {
                    Eventbus.$emit('select-tab', hash);
                },
                clear: function (field) {
                    Eventbus.$emit('clearErrors', field)
                },
                duplicateImageComponent: function (options) {
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


        // Close dropdown outside of the dropdown - used by backdrop
        window.closeDropdown = function (id) {
            Eventbus.$emit('close-dropdown', id);
        };
    </script>

    @livewireScripts

    <script src="{{ chief_cached_asset('chief-assets/back/js/native.js') }}"></script>

    @stack('custom-scripts-after-vue')

    <script>
        Livewire.hook('component.initialized', (component) => {
            // Hack to prevent livewire from setting the url to the sidebar url (as used for forms and fragments)
            if(component.effects.path !== undefined && (component.effects.path.includes('/fragment/') || component.effects.path.includes('/forms/'))) {
                component.effects.path = undefined;
            }
        });
    </script>

    @foreach(app(ChiefPluginSections::class)->getFooterSections() as $footerSection)
        @include($footerSection)
    @endforeach
</body>
</html>

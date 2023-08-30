<script src="{{ chief_cached_asset('js/main.js') }}"></script>

<!-- place to add custom vue components, right before the global Vue instance is created -->
@stack('custom-components')

@stack('custom-scripts')

<script src="{{ asset('/assets/back/js/vendor/slim.min.js') }}"></script>

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
            generateSitemap: function (id, options) {
                Eventbus.$emit('open-modal', id, options);
                axios.post('{{route('chief.back.sitemap.generate')}}', {
                    _method: 'POST'
                }).then((response) => {
                    Eventbus.$emit('close-modal', id);
                }).catch((errors) => {
                    alert('error');
                })
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

<script src="{{ chief_cached_asset('js/native.js') }}"></script>

@stack('custom-scripts-after-vue')

</body>
</html>

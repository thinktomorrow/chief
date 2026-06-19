@push ('custom-scripts')
    <script type="module">
        window.Redactor.options = {
            lang: 'nl',
            @if (chiefAdmin()->hasRole('developer'))
            plugins: ['alignment', 'custom-classes', 'rich-links', 'lorem-ipsum', 'counter'],
            buttons: ['html', 'undo', 'format', 'bold', 'italic', 'underline', 'sup', 'sub', 'lists', 'file', 'link'],
            @else
            plugins: ['alignment', 'custom-classes', 'rich-links', 'counter'],
            buttons: ['undo', 'format', 'bold', 'italic', 'underline', 'sup', 'sub', 'lists', 'file', 'link'],
            @endif
            formatting: ['h3', 'h4', 'p'],
            customClasses: [
                { title: 'link als knop', class: 'btn btn-default', tags: ['a'] },
                { title: 'link als primaire knop', class: 'btn btn-blue', tags: ['a'] },
                { title: 'link as secundaire knop', class: 'btn btn-outline-blue', tags: ['a'] },
                { title: 'geen knop weergave', class: '', tags: ['a'] },
            ],
            definedlinks: '{{ route('chief.api.internal-links') }}',
            toolbarFixed: false,
        };

        function loadRedactorInstances(container) {
            var elements = container.querySelectorAll('[data-editor], textarea.redactor-editor');

            elements.forEach((el) => {
                if (el.dataset.redactorInitialized) {
                    return;
                }

                var customOptions = {};

                if (el.hasAttribute('data-custom-redactor-options')) {
                    customOptions = JSON.parse(el.getAttribute('data-custom-redactor-options'));
                }

                customOptions['callbacks'] = {
                    ...(customOptions['callbacks'] || {}),
                    changed: function (e) {
                        let content = this.source.getCode();
                        el.value = content;
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                    },
                };

                window.Redactor(el, customOptions);
                el.dataset.redactorInitialized = true;
            });
        }

        document.addEventListener('form-dialog-opened', (event) => {
            setTimeout(() => {
                const dialogEl = event.detail.componentId
                    ? document.querySelector(`[wire\\:id="${event.detail.componentId}"]`)
                    : document;
                loadRedactorInstances(dialogEl);
            }, 0);
        });

        loadRedactorInstances(document);
    </script>
@endpush

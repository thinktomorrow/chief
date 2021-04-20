@if(chiefAdmin())
    @php
        // find model by this url, get manager, set model and then get route for edit
        $editUrl = null;

        try {
            $path = request()->path();

            // Remove the locale if any
            if(0 === strpos($path, app()->getLocale() . '/') || $path === app()->getLocale()) {
                $path = substr($path, strlen(app()->getLocale() . '/'));
            }

            $manager = app(\Thinktomorrow\Chief\Management\Managers::class)->findByUrl($path, app()->getLocale());
            $editUrl = $manager->can('edit') ? $manager->route('edit') : null;

        } catch(Exception $e) {
            //
        }

        $inPreviewMode = \Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode::fromRequest()->check();
        $previewModeToggleUrl = route('chief.front.preview');
    @endphp

    <style type="text/css">
        .chief_widget {
            position: fixed;
            right: 0;
            left: 0;
            bottom: 1rem;
            z-index: 9999;
            width: 250px;
            margin: 0 auto;
            background-color: white;
            color: #533D4D;
            border: 1px solid #FCE4DD;
            border-radius: 9999px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            overflow: hidden;
            text-align: center;
            line-height: 1.5;
        }
        .chief_widget .inset-block {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #000000 !important;
            padding: .6rem 0;
            cursor: pointer;
            flex-grow: 1;
            align-self: center;
        }
        .chief_widget a:hover { background-color: #fdf6f5; }
        .chief_widget .boundary:not(:last-child) { border-right: 1px solid #FCE4DD; }
        .chief_widget.cloaked { display: none; }
    </style>

    <div id="js-chiefAdminToast" class="chief_widget">
        @if($editUrl)
            <a href="{{ $editUrl }}" title="Bewerk deze pagina in chief" class="inset-block boundary" aria-label="edit page">
                <svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path><line x1="16" y1="8" x2="2" y2="22"></line><line x1="17.5" y1="15" x2="9" y2="15"></line>
                </svg>
            </a>
        @endif

        <a class="inset-block boundary" href="{{ $previewModeToggleUrl }}" title="{{ $inPreviewMode ? 'Schakel preview uit: verberg offline pagina\'s' : 'Schakel preview aan: toon offline pagina\'s' }}">{{ $inPreviewMode ? 'Admin view' : 'Live view' }}</a>

        <a class="inset-block boundary" data-chief-toast-close title="sluiten" aria-label="close">
            <svg id="x" class="inline-block" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </a>
    </div>

    <script>
        var chiefAdminToast = document.getElementById('js-chiefAdminToast');
        var chiefAdminToastClose = chiefAdminToast.querySelector('[data-chief-toast-close]');

        chiefAdminToastClose.addEventListener('click', function() {
            chiefAdminToast.classList.add('cloaked');
        });
    </script>
@endif

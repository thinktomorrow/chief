@if(chiefAdmin())
<?php

    // find model by this url, get manager, set model and then get route for edit
    $editUrl = null;

    try{
        $manager = app(\Thinktomorrow\Chief\Management\Managers::class)->findByUrl(request()->path(), app()->getLocale());

        $editUrl = $manager->can('edit') ? $manager->route('edit') : null;

    } catch(Exception $e)
    {
        //
    }

    $inPreviewMode = session()->get('preview-mode', false);
    $previewModeToggleUrl = route('chief.front.preview');

?>
<!-- chief admin toast -->
<div id="chiefAdminToast" style="position: fixed; top: 0; left: 0; background-color: white; color: black; z-index: 9999; margin-left: -1px; margin-top: -1px; border: 1px solid; border-bottom-right-radius: .5em;">
    @if($editUrl)
        <a style="display:inline-block; padding:.6rem;" href="{{ $editUrl }}" title="Bewerk deze pagina in chief">
            <svg style="display:inline-block;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path><line x1="16" y1="8" x2="2" y2="22"></line><line x1="17.5" y1="15" x2="9" y2="15"></line>
            </svg>
        </a> |
    @endif
    <a style="display: inline-block; padding:.6rem;" href="{{ $previewModeToggleUrl }}" title="{{ $inPreviewMode ? 'Schakel preview uit: verberg offline pagina\'s' : 'Schakel preview aan: toon offline pagina\'s' }}">Preview {{ $inPreviewMode ? 'aan' : 'uit' }}</a>
    <span style="display: inline-block; padding:.6rem; cursor:pointer;" data-chief-toast-close>x</span>
</div>
<script>
    var chiefAdminToast = document.getElementById('chiefAdminToast');

    chiefAdminToast.querySelector('[data-chief-toast-close]').addEventListener('click', function(){
        chiefAdminToast.classList.add('hidden');
    });
</script>
@endif

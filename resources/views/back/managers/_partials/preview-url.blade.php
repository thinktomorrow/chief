@if(contract($manager, \Thinktomorrow\Chief\Management\ManagerThatPreviews::class))
    <a class="block squished-s --link-with-bg" href="{!! $manager->previewUrl() !!}">Bekijk preview</a>
@endif

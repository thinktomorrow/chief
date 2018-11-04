@if(contract($manager, \Thinktomorrow\Chief\Management\ManagerThatPublishes::class))
    @if($manager->isPublished())

        <a data-submit-form="draftForm-{{ $manager->managerDetails()->id }}" class="block squished-s text-warning --link-with-bg">Haal offline</a>

        <form class="--hidden" id="draftForm-{{ $manager->managerDetails()->id }}" action="{{ route('chief.back.pages.draft',  $manager->managerDetails()->id ) }}" method="POST">
            {{ csrf_field() }}
            <button type="submit">Unpublish</button>
        </form>

    @elseif($manager->isDraft())

        <a data-submit-form="publishForm-{{ $manager->managerDetails()->id }}" class="block squished-s --link-with-bg">Zet online</a>

        <form class="--hidden" id="publishForm-{{ $manager->managerDetails()->id }}" action="{{ route('chief.back.pages.publish',  $manager->managerDetails()->id ) }}" method="POST">
            {{ csrf_field() }}
            <button type="submit">Publish</button>
        </form>

    @endif
@endif
@if($manager->isAssistedBy('publish'))
    <a data-submit-form="draftForm-{{ $manager->details()->id }}" class="block p-3 text-warning --link-with-bg">Haal offline</a>

    <form class="hidden" id="draftForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('publish')->route('unpublish') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit">Unpublish</button>
    </form>
@endif

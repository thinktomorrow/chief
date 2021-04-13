@if($manager->isAssistedBy('archive'))
    <a data-submit-form="unarchiveForm-{{ $manager->details()->id }}" class="block p-3 text-warning --link-with-bg">Herstel</a>

    <form class="hidden" id="unarchiveForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('archive')->route('unarchive') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit">Herstel</button>
    </form>
@endif
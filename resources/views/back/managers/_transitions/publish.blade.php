@if($manager->isAssistedBy('publish'))
    <a data-submit-form="publishForm-{{ $manager->details()->id }}" class="block p-3 --link-with-bg">Zet online</a>

    <form class="hidden" id="publishForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('publish')->route('publish') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit">Publish</button>
    </form>
@endif

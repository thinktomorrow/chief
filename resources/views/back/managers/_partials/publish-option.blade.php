@if($manager->can('update'))
    @if($manager->isAssistedBy('publish'))
        @if($manager->assistant('publish')->isPublished())

            <a data-submit-form="draftForm-{{ $manager->details()->id }}" class="block squished-s text-warning --link-with-bg">Haal offline</a>

            <form class="--hidden" id="draftForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('publish')->route('draft') }}" method="POST">
                {{ csrf_field() }}
                <button type="submit">Unpublish</button>
            </form>

        @elseif($manager->assistant('publish')->isDraft())

            <a data-submit-form="publishForm-{{ $manager->details()->id }}" class="block squished-s --link-with-bg">Zet online</a>

            <form class="--hidden" id="publishForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('publish')->route('publish') }}" method="POST">
                {{ csrf_field() }}
                <button type="submit">Publish</button>
            </form>

        @endif
    @endif
@endif

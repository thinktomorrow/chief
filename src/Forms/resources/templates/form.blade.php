<x-chief-form::window
    :title="$getTitle()"
    :refresh-url="$getRefreshUrl()"
    :tags="$getId()"
    :class="$getLayout()->class()"
>
    <form
        {{ $attributes->merge($getCustomAttributes()) }}
        id="{{ $getElementId() }}"
        method="POST"
        enctype="multipart/form-data"
        role="form"
        action="{{ $getAction() }}"
    >
        @csrf
        @if($getActionMethod() == 'PUT')
            @method('put')
        @endif

        <div class="relative space-y-6">
            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach

            <button type="submit" class="btn btn-primary">
                Opslaan
            </button>

            <div data-form-refreshed-notification class="absolute bottom-0 right-0 hidden scale-0 animate-pop-in-out">
                <span class="label label-success label-xs"> Opgeslagen </span>
            </div>
        </div>
    </form>
</x-chief-form::window>

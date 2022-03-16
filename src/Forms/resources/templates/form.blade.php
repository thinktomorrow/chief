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

        <div class="space-y-6">
            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach

            <button type="submit" class="btn btn-primary">
                Opslaan
            </button>

            <p class="text-grey-500">{{ time() }}</p>
        </div>
    </form>
</x-chief-form::window>

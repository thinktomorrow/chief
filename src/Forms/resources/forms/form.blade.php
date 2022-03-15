<x-chief::window
    :title="$getTitle()"
    :refresh-url="$getRefreshUrl()"
    :tags="$getId()"
>
    <div class="relative space-y-4">
        <form {{ $attributes->merge($getCustomAttributes()) }}
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

            <p class="absolute bottom-0 right-0 opacity-50">{{ time() }}</p>

            <div class="space-y-6">
                @foreach($getComponents() as $childComponent)
                    {{ $childComponent }}
                @endforeach

                <button type="submit" class="btn btn-primary">
                    Opslaan
                </button>
            </div>
        </form>
    </div>
</x-chief::window>

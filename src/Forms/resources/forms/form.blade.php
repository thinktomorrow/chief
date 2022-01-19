<x-chief::window
        :title="$getTitle()"
        :refresh-url="$getRefreshUrl()"
        :tags="$getId()"
>
    <div class="space-y-4">
        <form {{ $attributes->merge($getCustomAttributes()) }}
              id="{{ $getUniqueTagId() }}"
              method="POST"
              enctype="multipart/form-data"
              role="form"
              action="{{ $getAction() }}"
        >
            @csrf
            @if($getActionMethod() == 'PUT')
                @method('put')
            @endif

            <p>{{ time() }}</p>

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

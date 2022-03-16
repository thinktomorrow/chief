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

            <div class="flex flex-wrap items-end justify-between gap-3">
                <button type="submit" class="btn btn-primary">
                    Opslaan
                </button>

                <div data-form-refreshed-notification class="hidden scale-0 animate-pop-in-out">
                    <div class="label label-success label-sm">
                        Opgeslagen
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-chief-form::window>

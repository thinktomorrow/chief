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

        @if(isset($order))
            <input type="hidden" name="order" value="{{ $order ?? 0 }}">
        @endif

        <div class="relative space-y-6">
            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach

            <button type="submit" class="relative btn btn-primary" style="z-index: 1;">
                Opslaan
            </button>

            <div
                data-form-refreshed-notification
                class="absolute bottom-0 right-0 hidden scale-0 animate-pop-in-out"
            >
                <span class="label label-success label-xs"> Opgeslagen </span>
            </div>

            <div
                data-form-unsaved-notification
                class="absolute bottom-0 right-0 hidden animate-pop-in"
            >
                <span class="label label-grey label-xs">
                    <span> Nog niet opgeslagen </span>
                </span>
            </div>
        </div>
    </form>
</x-chief-form::window>

<x-chief-form::window
    :title="$getTitle()"
    :refresh-url="$getRefreshUrl()"
    :tags="$getTagsAsString()"
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
            @if($getDescription())
                <p class="body body-dark">
                    {!! $getDescription() !!}
                </p>
            @endif

            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach

            <button type="submit" class="gap-2 btn btn-primary">
                Opslaan
                <svg data-form-submit-spinner class="hidden w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
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

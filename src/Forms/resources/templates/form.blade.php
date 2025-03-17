<x-chief-form::window
    :title="$getTitle()"
    :description="$getDescription()"
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
        
        @if($getActionMethod())
            @method($getActionMethod())
        @endif

        @if (isset($order))
            <input type="hidden" name="order" value="{{ $order ?? 0 }}" />
        @endif

        <div class="relative">
            @foreach ($getComponents() as $_component)
                {{ $_component }}
            @endforeach

            <x-chief-table::button type="submit" variant="blue">
                Opslaan
                <svg
                    data-form-submit-spinner
                    class="hidden h-5 w-5 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
            </x-chief-table::button>

            <div data-form-refreshed-notification class="absolute bottom-0 right-0 hidden scale-0 animate-pop-in-out">
                <span class="label label-success label-xs">Opgeslagen</span>
            </div>

            <div data-form-unsaved-notification class="absolute bottom-0 right-0 hidden animate-pop-in">
                <span class="label label-grey label-xs">
                    <span>Nog niet opgeslagen</span>
                </span>
            </div>
        </div>
    </form>
</x-chief-form::window>

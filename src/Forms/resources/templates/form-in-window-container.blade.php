<x-chief-form::window
    :title="$getTitle()"
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
    :class="$getLayout()->class()"
>
    <div class="space-y-4">
        @include($getWindowView())
    </div>
</x-chief-form::window>

<x-chief-form::window
    :title="$getTitle()"
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
    :class="$getLayout()->class()"
>
    @include($getWindowView())
</x-chief-form::window>

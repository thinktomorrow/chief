<x-chief-form::window
    :title="$getTitle()"
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
>
    @include($getWindowView())
</x-chief-form::window>

<x-chief-form::window.inline
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
>
    @include($getWindowView())
</x-chief-form::window.inline>

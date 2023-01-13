<x-chief-form::window.inline
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
    :tags="$getTagsAsString()"
>
    @include($getWindowView())
</x-chief-form::window.inline>

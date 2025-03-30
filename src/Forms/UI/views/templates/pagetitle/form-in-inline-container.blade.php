<x-chief-form::window.inline
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
    :tags="$getTagsAsString()"
>
    @include($getPreviewView())
</x-chief-form::window.inline>

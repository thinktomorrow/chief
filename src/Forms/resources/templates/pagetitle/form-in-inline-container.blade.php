<x-chief-form::window.inline
    :edit-url="$getWindowAction()"
    :refresh-url="$getRefreshUrl()"
>
    <div class="space-y-4">
        @include($getWindowView())
    </div>
</x-chief-form::window.inline>


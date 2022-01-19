<x-chief::window
        :title="$getTitle()"
        :edit-url="$getWindowAction()"
        :refresh-url="$getRefreshUrl()"
>
    <div class="space-y-4">
        @include($getWindowView())
    </div>
</x-chief::window>


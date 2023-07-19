<x-chief::dialog wired>
    @if($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
{{--        <form class="flex max-md:flex-col gap-8 w-full xs:w-96 sm:w-128 md:w-160 lg:w-192 max-h-[80vh] overflow-y-auto">--}}
        <form class="flex overflow-y-auto gap-8">
            @if($previewFile && $previewFile->isExternalAsset)
                @include('chief-assets::livewire.file-edit-external')
            @else
                @include('chief-assets::livewire.file-edit-local')
            @endif
        </form>
    @endif
</x-chief::dialog>

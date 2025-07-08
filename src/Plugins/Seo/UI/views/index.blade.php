<x-chief::page.template title="Seo" container="md">
    <x-chief::window>
        <div class="-my-4 divide-y divide-grey-100">
            <div class="flex items-center justify-between py-3">
                <a
                    href="{{ route('chief.seo.assets.index') }}"
                    title="Alt"
                    class="body-dark font-medium hover:underline"
                >
                    Alt teksten beheren
                </a>

                <x-chief::button
                    href="{{ route('chief.seo.assets.index') }}"
                    title="Alt"
                    size="sm"
                    variant="grey"
                >
                    <x-chief::icon.quill-write />
                </x-chief::button>
            </div>
        </div>
    </x-chief::window>
</x-chief::page.template>

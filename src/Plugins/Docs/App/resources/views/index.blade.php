<x-chief::page.template title="Documentatie" container="lg">
    <x-chief::window>
        <ul class="flex flex-col gap-3">
            @foreach ($files as $file)
                <li>
                    <x-chief::link variant="blue" :href="route('chief.docs.show', $file)">
                        {{ config('chief-docs.pages.'.$file.'.title') ?: Str::headline($file) }}
                    </x-chief::link>
                </li>
            @endforeach
        </ul>
    </x-chief::window>
</x-chief::page.template>

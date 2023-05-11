<x-chief::page.template title="Vaste teksten">
    <x-slot name="hero">
        <x-chief::page.hero title="Vaste teksten" class="max-w-3xl"/>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="divide-y card divide-grey-100">
            @foreach($pages as $page)
                @php
                    $completionPercentage = $page->completionPercentage();
                @endphp

                <div @class([
                    'flex items-center justify-between gap-4',
                    'pt-3' => !$loop->first,
                    'pb-3' => !$loop->last,
                ])>
                    <span class="space-x-1 mt-0.5">
                        <a
                            href="{{ route('squanto.edit',$page->slug()) }}"
                            title="{{ ucfirst($page->label()) }}"
                            class="font-medium body-dark hover:underline"
                        >
                            {{ ucfirst($page->label()) }}
                        </a>

                        <span class="label label-grey label-xs">
                            {{ $completionPercentage }}%
                        </span>
                    </span>

                    <a href="{{ route('squanto.edit',$page->slug()) }}" class="flex-shrink-0 link link-primary">
                        <x-chief::icon-button type="edit"></x-chief-icon-button>
                    </a>
                </div>
            @endforeach
        </div>
    </x-chief::page.grid>
</x-chief::page.template>

{{-- TODO: add inner shadow if rows have horizontal scroll --}}
@props([
    'filters' => [],
    'actions' => null,
])

<div data-table-container class="border divide-y card-without-padding border-grey-200 divide-grey-200">
    @if (count($filters) > 0)
        <div class="flex items-start justify-start gap-12 p-6">
            <div class="w-full">
                <form data-form-submit-on-change method="GET" class="row-start-end gutter-3">
                    @foreach ($filters as $filter)
                        <div @class([
                            'w-full sm:w-1/2 md:w-1/3' => !$actions,
                            'w-full sm:w-1/2 xl:w-1/3' => $actions,
                            'hidden' => $filter->getType() == 'hidden'
                        ])>
                            {!! $filter->render() !!}
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    @endif

    @if($actions)
         <div class="p-6 space-y-4">
            <div data-bulk-actions-container class="hidden">
                <div class="flex items-center justify-end gap-4">
                    <p class="body-base text-grey-500">
                        <span data-bulk-actions-counter class="siblings:bulk-actions-counter-condition">0</span>
                        <span class="hidden bulk-actions-counter-is-1:inline">item</span>
                        <span class="bulk-actions-counter-is-1:hidden">items</span>
                        geselecteerd
                    </p>

                        <dropdown class="relative z-20">
                            <span slot="trigger" slot-scope="{ toggle }" @click="toggle" class="btn btn-primary">
                                ...
                            </span>

                            <div v-cloak class="dropdown-content">
                                {{ $actions }}
                            </div>
                        </dropdown>
                </div>
            </div>
        </div>
    @endif

    {{-- The specific height value is necessary in order for the sticky table headers to work. This because of an issue
    combining sticky element within a container with non-default overflow values. The absolute position of the table
    element is necessary to fix a bug where the table would partially overflow its container, even though it has
    overflow-x-scroll. --}}
    <div class="w-full h-[80vh] relative">
        <div @class([
            'overflow-x-scroll whitespace-nowrap absolute inset-0',
            'rounded-xl' => !$actions && !$filters
        ])>
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <x-chief::table.row>
                        {{ $header }}
                    </x-chief::table.row>
                </thead>

                <tbody>
                    {{ $body }}
                </tbody>
            </table>
        </div>
    </div>
</div>

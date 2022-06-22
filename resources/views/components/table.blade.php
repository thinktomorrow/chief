@props([
    'search' => null,
    'actions' => null,
    'filters' => null,
])

<div data-table-container class="border divide-y card-without-padding border-grey-200 divide-grey-200">
    <div class="p-6 space-y-4">
        <div class="flex items-start justify-between gap-6">
            <div class="w-96 shrink-0">
                {{ $search }}
            </div>

            <div class="flex items-start justify-end gap-6">
                <div data-bulk-actions-container class="hidden">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm body-base body-dark">
                            <span data-bulk-actions-counter class="siblings:bulk-actions-counter-condition">0</span>
                            <span class="hidden bulk-actions-counter-is-1:inline">item</span>
                            <span class="bulk-actions-counter-is-1:hidden">items</span>
                            geselecteerd
                        </p>

                        <dropdown class="relative z-20">
                            <span slot="trigger" slot-scope="{ toggle }" @click="toggle" class="btn btn-primary">
                                Bulkactie toepassen
                            </span>

                            <div v-cloak class="dropdown-content">
                                {{ $actions }}
                            </div>
                        </dropdown>
                    </div>
                </div>

                <dropdown class="relative z-20">
                    <span slot="trigger" slot-scope="{ toggle }" @click="toggle" class="btn btn-grey">
                        Filteren
                    </span>

                    <div v-cloak class="dropdown-content">
                        {{ $filters }}
                    </div>
                </dropdown>
            </div>
        </div>
    </div>

    {{-- The specific height value is necessary in order for the sticky table headers to work.
    This because of an issue combining sticky element within a container with non-default overflow values --}}
    <div class="overflow-x-scroll whitespace-nowrap h-[80vh]">
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

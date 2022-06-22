@props([
    'actions' => null
])

<div data-table-container class="space-y-6">
    @if($actions)
        <div data-bulk-actions-container class="card-without-padding" hidden>
            <div class="flex items-center justify-between gap-6 px-4 py-3">
                <p class="body-base body-dark">
                    <span data-bulk-actions-counter class="siblings:bulk-actions-counter-condition">0</span>
                    <span class="hidden bulk-actions-counter-is-1:inline">item</span>
                    <span class="bulk-actions-counter-is-1:hidden">items</span>
                    geselecteerd
                </p>

                <div clas="flex items-start gap-2">
                    {{ $actions }}
                </div>
            </div>
        </div>
    @endif

    <div class="card-without-padding">
        {{-- The specific height value is necessary in order for the sticky table headers to work.
        This because of an issue combining sticky element within a container with non-default overflow values --}}
        <div class="overflow-x-scroll rounded-lg whitespace-nowrap h-[80vh] border border-grey-200">
            <table class="min-w-full border-separate rounded-xl border-spacing-0">
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

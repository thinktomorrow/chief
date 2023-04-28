@props([
    'filters' => [],
    'actions' => null,
    'sticky' => false,
])

{{-- TODO: add inner shadow if rows have horizontal scroll --}}
{{-- <div data-table-container class="border divide-y card-without-padding border-grey-200 divide-grey-200"> --}}
<div data-table-container {{ $attributes }}>
    @if (count($filters) > 0)
        <div class="flex items-start justify-start gap-12 p-6">
            <div class="w-full">
                <form data-form-submit-on-change method="GET" class="row-start-end gutter-3">
                    @foreach ($filters as $filter)
                        <div @class([
                            'w-full sm:w-1/2 md:w-1/3',
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
        <div data-bulk-actions-container class="hidden px-8 py-2 border-t border-grey-200 bg-primary-50">
            <div class="flex items-center justify-between gap-6">
                <p class="leading-6 body body-dark">
                    <span data-bulk-actions-counter class="siblings:bulk-actions-counter-condition">0</span>
                    <span class="hidden bulk-actions-counter-is-1:inline">item</span>
                    <span class="bulk-actions-counter-is-1:hidden">items</span>
                    geselecteerd.

                    <span class="link link-primary">Alle X items selecteren.</span>
                    <span class="link link-primary">Alle items deselecteren.</span>
                </p>

                {{-- <div class="flex flex-wrap items-start justify-start gap-3">
                    {{ $actions }}
                </div> --}}
            </div>
        </div>
    @endif

    @if ($sticky)
        {{-- The specific height value is necessary in order for the sticky table headers to work. This because of an issue
        combining sticky element within a container with non-default overflow values. The absolute position of the table
        element is necessary to fix a bug where the table would partially overflow its container, even though it has
        overflow-x-auto. --}}
        <div class="w-full h-[80vh] relative">
            <div class="absolute inset-0 overflow-x-auto whitespace-nowrap">
                <table class="min-w-full border-separate border-spacing-0">
                    @isset($header)
                        <thead {{ $header->attributes }}>
                            <x-chief::table.row>
                                {{ $header }}
                            </x-chief::table.row>
                        </thead>
                    @endisset

                    @isset($body)
                        <tbody {{ $body->attributes }}>
                            {{ $body }}
                        </tbody>
                    @endisset
                </table>
            </div>
        </div>
    @else
        <div class="relative w-full">
            <div class="overflow-x-auto whitespace-nowrap">
                <table class="min-w-full border-separate border-spacing-0">
                    @isset($header)
                        <thead {{ $header->attributes }}>
                            <x-chief::table.row>
                                {{ $header }}
                            </x-chief::table.row>
                        </thead>
                    @endisset

                    @isset($body)
                        <tbody {{ $body->attributes }}>
                            {{ $body }}
                        </tbody>
                    @endisset
                </table>
            </div>
        </div>
    @endif
</div>

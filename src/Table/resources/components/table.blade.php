@php
    use Illuminate\Support\Facades\Route;
@endphp

@props([
    'filters' => [],
    'actions' => null,
    'sticky' => false,
    'bodyAttributes' => null,
])

{{-- TODO: add inner shadow if rows have horizontal scroll --}}
<div data-table-container class="card-without-padding divide-y divide-grey-200 border border-grey-200">
    @if (count($filters) > 0)
        <div class="flex items-start justify-start gap-6 p-6 max-sm:flex-wrap">
            <div class="w-full">
                <form data-form-submit-on-change method="GET" class="row-start-start gutter-1.5">
                    @foreach ($filters as $filter)
                        <div
                            @class(['w-full', 'sm:w-1/2 xl:w-1/3' => $filter->getType() != 'tags', 'hidden' => $filter->getType() == 'hidden'])
                        >
                            {!! $filter->render() !!}
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="shrink-0">
                <a href="/{{ Route::getCurrentRoute()->uri() }}" title="Reset alle filters" class="btn btn-grey">
                    Reset alle filters
                </a>
            </div>
        </div>
    @endif

    @if ($actions)
        <div data-bulk-actions-container class="hidden px-6 py-4">
            <div class="flex items-center gap-6">
                <p class="body text-grey-500">
                    <span data-bulk-actions-counter class="siblings:bulk-actions-counter-condition">0</span>
                    <span class="bulk-actions-counter-is-1:inline hidden">item</span>
                    <span class="bulk-actions-counter-is-1:hidden">items</span>
                    geselecteerd
                </p>

                <div class="flex flex-wrap items-start justify-start gap-3">
                    {{ $actions }}
                </div>
            </div>
        </div>
    @endif

    @if ($sticky)
        {{--
            The specific height value is necessary in order for the sticky table headers to work. This because of an issue
            combining sticky element within a container with non-default overflow values. The absolute position of the table
            element is necessary to fix a bug where the table would partially overflow its container, even though it has
            overflow-x-auto.
        --}}
        <div class="relative h-[80vh] w-full">
            <div
                @class([
                    'absolute inset-0 overflow-x-auto whitespace-nowrap',
                    'rounded-xl' => ! $actions && ! $filters,
                ])
            >
                <table class="min-w-full border-separate border-spacing-0">
                    @isset($header)
                        <thead>
                            <x-chief::table.row>
                                {{ $header }}
                            </x-chief::table.row>
                        </thead>
                    @endisset

                    @isset($body)
                        <tbody>
                            {{ $body }}
                        </tbody>
                    @endisset
                </table>
            </div>
        </div>
    @else
        <div class="relative w-full">
            <div
                @class([
                    'overflow-x-auto whitespace-nowrap',
                    'rounded-xl' => ! $actions && ! $filters,
                ])
            >
                <table class="min-w-full border-separate border-spacing-0">
                    @isset($header)
                        <thead>
                            <x-chief::table.row>
                                {{ $header }}
                            </x-chief::table.row>
                        </thead>
                    @endisset

                    @isset($body)
                        <tbody {{ $bodyAttributes }}>
                            {{ $body }}
                        </tbody>
                    @endisset
                </table>
            </div>
        </div>
    @endif
</div>

@php
    $rows = json_decode(json_encode([
        ['id' => 5, 'title' => 'new kid on the block'],
        ['id' => 6, 'title' => 'new kid on the block'],
        ['id' => 8, 'title' => 'new kid on the block', 'rows' => json_decode(json_encode([
            ['id' => 45, 'title' => 'new kid on the block'],
            ['id' => 77, 'title' => 'new kid on the blodfqdf'],
        ]))],
]));

    $listingComponent = \Thinktomorrow\Chief\TableNew\UI\Livewire\ArticleListing::class;

    $level = 0;
@endphp

<x-chief::page.layout>

    <livewire:is :component="$listingComponent" />

{{--    <div class="flex justify-center p-16">--}}
{{--        <div class="overflow-x-auto bg-white divide-y shadow-lg rounded-xl w-160 whitespace-nowrap ring-1 ring-grey-200 divide-grey-200">--}}
{{--            --}}{{-- ACTIES--}}
{{--            BULKACTIES (context)--}}
{{--            ROWACTIES--}}
{{--            FILTER--}}
{{--            SORT --}}

{{--            <div class="flex justify-between px-4 py-4 form-light">--}}
{{--                <div class="relative">--}}
{{--                    <input--}}
{{--                        type="text"--}}
{{--                        name="search"--}}
{{--                        id="search"--}}
{{--                        placeholder="Zoek op titel, id ..."--}}
{{--                        class="py-2 pr-3 leading-5 rounded-lg shadow pl-9 ring-grey-200 ring-1"--}}
{{--                    />--}}

{{--                    <div class="absolute left-2 top-2">--}}
{{--                        <svg class="w-5 h-5 text-grey-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">--}}
{{--                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />--}}
{{--                        </svg>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <button type="button" class="inline-flex gap-2 py-2 pl-3 pr-2 font-medium leading-5 rounded-lg shadow ring-1 ring-grey-200 text-grey-700">--}}
{{--                    Bulk acties--}}
{{--                    <svg class="w-5 h-5 text-grey-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">--}}
{{--                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            <table class="min-w-full divide-y table-fixed divide-grey-200">--}}
{{--                <thead class="text-grey-800">--}}
{{--                    <tr>--}}
{{--                        <th scope="col" class="w-5 py-2 pl-4 pr-3 text-left">--}}
{{--                            <div class="flex items-center form-light">--}}
{{--                                <x-chief::input.checkbox name="all" id="all" />--}}
{{--                            </div>--}}
{{--                        </th>--}}

{{--                        <th scope="col" class="py-2 pl-3 pr-3 font-medium text-left">--}}
{{--                            <span class="leading-5">Titel</span>--}}
{{--                        </th>--}}

{{--                        <th scope="col" class="py-2 pl-3 pr-3 font-medium text-left">--}}
{{--                            <span class="leading-5">Id</span>--}}
{{--                        </th>--}}

{{--                        <th scope="col" class="py-2 pl-3 pr-4 font-medium text-left">--}}
{{--                            <span class="sr-only">Aanpassen</span>--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                </thead>--}}

{{--                <tbody class="divide-y divide-grey-200">--}}
{{--                    @foreach($rows as $row)--}}
{{--                        <tr>--}}
{{--                            <td class="w-5 py-2 pl-4 pr-3 text-left">--}}
{{--                                <div class="flex items-center form-light">--}}
{{--                                    <x-chief::input.checkbox name="{{ $row->id }}" id="{{ $row->id }}" />--}}
{{--                                </div>--}}
{{--                            </td>--}}

{{--                            <td class="py-2 pl-3 pr-3 text-left">--}}
{{--                                <span class="font-medium leading-5 text-grey-700">--}}
{{--                                    {{ $row->title }} {{ $row->id }}--}}
{{--                                </span>--}}
{{--                            </td>--}}

{{--                            <td class="py-2 pl-3 pr-3 text-left">--}}
{{--                                <span class="leading-5 text-grey-700">--}}
{{--                                    {{ $row->id }}--}}
{{--                                </span>--}}
{{--                            </td>--}}

{{--                            <td class="py-2 pl-3 pr-4 text-right">--}}
{{--                                <button type="button" class="font-medium leading-5 text-primary-500 hover:text-primary-600">--}}
{{--                                    Aanpassen--}}
{{--                                </button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}

{{--                        @if(isset($row->rows))--}}
{{--                            @foreach($row->rows as $childRow)--}}
{{--                                <tr>--}}
{{--                                    <td class="w-5 py-2 pl-4 pr-3 text-left">--}}
{{--                                        <div class="flex items-center form-light">--}}
{{--                                            <x-chief::input.checkbox name="{{ $row->id }}" id="{{ $row->id }}" />--}}
{{--                                        </div>--}}
{{--                                    </td>--}}

{{--                                    <td class="py-2 pl-3 pr-3 text-left">--}}
{{--                                        <div class="flex items-start gap-3">--}}
{{--                                            <div style="width: {{ 20 * $level }}px;" class="flex items-end">--}}
{{--                                                <svg class="w-5 h-5 text-grey-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"></path></svg>--}}
{{--                                            </div>--}}

{{--                                            <span class="font-medium leading-5 text-grey-700">--}}
{{--                                                {{ $childRow->title }} {{ $childRow->id }}--}}
{{--                                            </span>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}

{{--                                    <td class="py-2 pl-3 pr-3 text-left">--}}
{{--                                        <span class="leading-5 text-grey-700">--}}
{{--                                            {{ $childRow->id }}--}}
{{--                                        </span>--}}
{{--                                    </td>--}}

{{--                                    <td class="py-2 pl-3 pr-4 text-right">--}}
{{--                                        <button type="button" class="font-medium leading-5 text-primary-500 hover:text-primary-600">--}}
{{--                                            Aanpassen--}}
{{--                                        </button>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}

{{--            <div class="body-dark">--}}
{{--                <div class="py-3 pl-4 pr-4 text-left">--}}
{{--                    Pagination ...--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</x-chief::page.layout>

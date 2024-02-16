@php

    $rows = json_decode(json_encode([
        ['id' => 5, 'title' => 'new kid on the block'],
        ['id' => 6, 'title' => 'new kid on the block'],
        ['id' => 8, 'title' => 'new kid on the block', 'rows' => json_decode(json_encode([
            ['id' => 45, 'title' => 'new kid on the block'],
            ['id' => 77, 'title' => 'new kid on the blodfqdf'],
        ]))],
]));
@endphp

<x-chief::page.layout>
    <div class="flex justify-center p-16">
        <div class="w-160">
            {{-- ACTIES
            BULKACTIES (context)
            ROWACTIES
            FILTER
            SORT --}}

            <table class="w-full min-w-full divide-y table-fixed divide-grey-400">
                <thead>
                    <tr>
                        <th scope="col" class="w-6 py-2 pr-2 text-left">
                            <div class="flex items-center">
                                <input type="checkbox" name="all" id="all" class="w-4 h-4 decoration-primary-500"/>
                            </div>
                        </th>

                        <th scope="col" class="py-2 pl-2 pr-2 text-left">Id</th>
                        <th scope="col" class="py-2 pl-2 pr-2 text-left">Title</th>

                        <th scope="col" class="py-2 pl-2 text-left">
                            <span class="sr-only">Aanpassen</span>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-grey-300">
                    @foreach($rows as $row)
                        <tr>
                            <td class="py-1.5 pr-2 text-left w-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="{{ $row->id }}" id="{{ $row->id }}" class="w-4 h-4 decoration-primary-500"/>
                                </div>
                            </td>

                            <td class="py-1.5 pr-2 pl-2 text-left">
                                <span class="leading-5 text-grey-700">
                                    {{ $row->id }}
                                </span>
                            </td>

                            <td class="py-1.5 pl-2 pr-2 text-left">
                                <span class="leading-5 text-grey-700">
                                    {{ $row->title }} {{ $row->id }}
                                </span>
                            </td>

                            <td class="py-1.5 pl-2 text-right">
                                <button type="button" class="font-medium leading-5 text-primary-500 hover:text-primary-600">
                                    Aanpassen
                                </button>
                            </td>
                        </tr>

                        @if(isset($row->rows))
                            @foreach($row->rows as $childRow)
                                <tr>
                                    <td class="py-1.5 pr-2 text-left w-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="{{ $row->id }}" id="{{ $row->id }}" class="w-4 h-4 decoration-primary-500"/>
                                        </div>
                                    </td>

                                    <td class="py-1.5 pl-2 pr-2 text-left">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-grey-700" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"></path></svg>

                                            <span class="leading-5 text-grey-700">
                                                {{ $childRow->id }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="py-1.5 pl-2 text-left">
                                        <span class="leading-5 text-grey-700">
                                            {{ $childRow->title }} {{ $childRow->id }}
                                        </span>
                                    </td>

                                    <td class="py-1.5 pl-2 text-right">
                                        <button type="button" class="font-medium leading-5 text-primary-500 hover:text-primary-600">
                                            Aanpassen
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>

                {{-- <tfoot>
                    <tr>
                        <td class="py-2 pr-2 text-left">
                            pagination
                        </td>
                    </tr>
                </tfoot> --}}
            </table>
        </div>
    </div>
</x-chief::page.layout>

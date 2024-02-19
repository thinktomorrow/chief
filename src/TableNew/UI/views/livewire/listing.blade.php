<div>

    {{--    <input type="text" wire:model.live="filters.title">--}}
        @foreach($this->getFilters() as $filter)
            {!! $filter->render() !!}
        @endforeach

    <button type="button" wire:click="download">
        Download Invoice
    </button>


    <div class="flex justify-center p-16">
        <div class="overflow-x-auto bg-white divide-y shadow-lg rounded-xl w-160 whitespace-nowrap ring-1 ring-grey-200 divide-grey-200">
            {{-- ACTIES
            BULKACTIES (context)
            ROWACTIES
            FILTER
            SORT --}}

            <div class="flex justify-between px-4 py-4 form-light">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        id="search"
                        placeholder="Zoek op titel, id ..."
                        class="py-2 pr-3 leading-5 rounded-lg shadow pl-9 ring-grey-200 ring-1"
                    />

                    <div class="absolute left-2 top-2">
                        <svg class="w-5 h-5 text-grey-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <button type="button" class="inline-flex gap-2 py-2 pl-3 pr-2 font-medium leading-5 rounded-lg shadow ring-1 ring-grey-200 text-grey-700">
                    Bulk acties
                    <svg class="w-5 h-5 text-grey-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <table class="min-w-full divide-y table-fixed divide-grey-200">
                <thead class="text-grey-800">
                <tr>
                    <th scope="col" class="w-5 py-2 pl-4 pr-3 text-left">
                        <div class="flex items-center form-light">
                            <x-chief::input.checkbox name="all" id="all" />
                        </div>
                    </th>

                    <th scope="col" class="py-2 pl-3 pr-3 font-medium text-left">
                        <span class="leading-5">Titel</span>
                    </th>

                    <th scope="col" class="py-2 pl-3 pr-3 font-medium text-left">
                        <span class="leading-5">Id</span>
                    </th>

                    <th scope="col" class="py-2 pl-3 pr-4 font-medium text-left">
                        <span class="sr-only">Aanpassen</span>
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-grey-200">
                    @foreach($this->getModels() as $model)
                        @php
                            $columns = $this->getRow($model);
                        @endphp
                        <tr wire:key="{{ $this->getRowKey($model)  }}">
                            <td class="w-5 py-2 pl-4 pr-3 text-left">
                                <div class="flex items-center form-light">
                                    <x-chief::input.checkbox name="{{ $this->getRowKey($model)  }}" id="{{ $this->getRowKey($model)  }}" />
                                </div>
                            </td>

                            @foreach($columns as $column)
                                <td class="py-2 pl-3 pr-3 text-left">
                                <span class="font-medium leading-5 text-grey-700">
                                    {{$column}}
                                </span>
                                </td>
                            @endforeach

                            <td class="py-2 pl-3 pr-4 text-right">
                                <button type="button" class="font-medium leading-5 text-primary-500 hover:text-primary-600">
                                    Aanpassen
                                </button>
                            </td>
                    @endforeach

                    @if($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable)
                        @foreach($model->getChildren() as $childModel)
                            @php
                                $columns = $this->getRow($model);
                            @endphp
                            <tr wire:key="{{ $this->getRowKey($model)  }}">
                                <td class="w-5 py-2 pl-4 pr-3 text-left">
                                    <div class="flex items-center form-light">
                                        <x-chief::input.checkbox name="{{ $this->getRowKey($model)  }}" id="{{ $this->getRowKey($model)  }}" />
                                    </div>
                                </td>

                                @foreach($columns as $column)
                                    <td class="py-2 pl-3 pr-3 text-left">
                                <span class="font-medium leading-5 text-grey-700">
                                    {{$column}}
                                </span>
                                    </td>
                                @endforeach

                                <td class="py-2 pl-3 pr-4 text-right">
                                    <button type="button" class="font-medium leading-5 text-primary-500 hover:text-primary-600">
                                        Aanpassen
                                    </button>
                                </td>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="body-dark">
                <div class="py-3 pl-4 pr-4 text-left">
                    Pagination ...
                </div>
            </div>
        </div>
    </div>
</div>


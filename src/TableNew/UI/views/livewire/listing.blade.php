<div>

    <div class="flex justify-center p-16">
        <div
            class="overflow-x-auto bg-white divide-y shadow-lg rounded-xl w-160 whitespace-nowrap ring-1 ring-grey-200 divide-grey-200">
            {{-- ACTIES
            BULKACTIES (context)
            ROWACTIES
            FILTER
            SORT --}}

            <div class="flex justify-between px-4 py-4 form-light">

                @foreach($this->getFilters() as $filter)
                    {!! $filter->render() !!}
                @endforeach

                <button type="button"
                        class="inline-flex gap-2 py-2 pl-3 pr-2 font-medium leading-5 rounded-lg shadow ring-1 ring-grey-200 text-grey-700">
                    Bulk acties
                    <svg class="w-5 h-5 text-grey-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <table class="min-w-full divide-y table-fixed divide-grey-200">
                <thead class="text-grey-800">
                <tr>
                    <th scope="col" class="w-5 py-2 pl-4 pr-3 text-left">
                        <div class="flex items-center form-light">
                            <x-chief::input.checkbox name="all" id="all"/>
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
                                <x-chief::input.checkbox name="{{ $this->getRowKey($model)  }}"
                                                         id="{{ $this->getRowKey($model)  }}"/>
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
                    </tr>

                    {{-- nested rows --}}
                    @if($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable)
                        @foreach($model->getChildren() as $childModel)
                            @php
                                $columns = $this->getRow($childModel);
                            @endphp
                            <tr wire:key="{{ $this->getRowKey($childModel)  }}">
                                <td class="w-5 py-2 pl-4 pr-3 text-left">
                                    <div class="flex items-center form-light">
                                        <x-chief::input.checkbox name="{{ $this->getRowKey($childModel)  }}"
                                                                 id="{{ $this->getRowKey($childModel)  }}"/>
                                    </div>

                                    <div style="width: 20px;" class="flex items-end">
                                        <svg class="w-5 h-5 text-grey-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"></path></svg>
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
                                    <button type="button"
                                            class="font-medium leading-5 text-primary-500 hover:text-primary-600">
                                        Aanpassen
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach

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


@php
    $results = $this->getModels();

    $resultsWithIndents = [];

    foreach ($results as $model) {
        $indent = 0;
        $resultsWithIndents[] = [
            'model' => $model,
            'indent' => $indent,
        ];

        if ($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable) {
            $indent = 1;
            foreach ($model->getChildren() as $_model) {
                $resultsWithIndents[] = [
                    'model' => $_model,
                    'indent' => $indent,
                ];

                if ($_model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable) {
                    $indent = 2;
                    foreach ($_model->getChildren() as $__model) {
                        $resultsWithIndents[] = [
                            'model' => $__model,
                            'indent' => $indent,
                        ];
                    }
                }
            }
        }
    }
@endphp

<div>
    <div
        class="divide-bui-grey-200 ring-bui-grey-200 divide-y overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-lg ring-1"
    >
        <table class="divide-bui-grey-200 min-w-full table-fixed divide-y">
            <thead>
                <tr>
                    <th scope="col" class="w-5 py-2 pl-4">
                        <div class="flex items-center">
                            <x-chief::input.checkbox name="all" id="all" />
                        </div>
                    </th>

                    <th scope="col" class="py-2 pl-3 text-left">
                        <span class="text-bui-grey-950 text-sm/5 font-medium">Titel</span>
                    </th>

                    <th scope="col" class="py-2 pl-3 text-left">
                        <span class="text-bui-grey-950 text-sm/5 font-medium">Status</span>
                    </th>

                    <th scope="col" class="py-2 pl-3 text-left">
                        <span class="text-bui-grey-950 text-sm/5 font-medium">Aangepast</span>
                    </th>

                    <th scope="col" class="py-2 pl-3 pr-4 text-right">
                        <span class="text-bui-grey-950 text-sm/5 font-medium">Aanpassen</span>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-bui-grey-200 divide-y">
                @foreach ($resultsWithIndents as $result)
                    @php
                        $model = $result['model'];
                        $indent = $result['indent'];
                    @endphp

                    <tr wire:key="{{ $this->getRowKey($model) }}">
                        <td class="w-5 py-2 pl-4 text-left">
                            <div class="flex items-center">
                                <x-chief::input.checkbox
                                    name="{{ $this->getRowKey($model)  }}"
                                    id="{{ $this->getRowKey($model)  }}"
                                />
                            </div>
                        </td>

                        @foreach ($this->getRow($model) as $column)
                            <td class="py-2 pl-3 text-left">
                                <div class="flex gap-1.5">
                                    @if ($loop->first && $indent > 0)
                                        <div class="flex justify-end" style="width: {{ 20 + ($indent - 1) * 26 }}px">
                                            <svg
                                                class="text-bui-grey-900 h-5 w-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor"
                                                viewBox="0 0 256 256"
                                            >
                                                <path
                                                    d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"
                                                ></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <span class="text-bui-grey-900 leading-5">
                                        {!! $column !!}
                                    </span>
                                </div>
                            </td>
                        @endforeach

                        <td class="py-2 pl-3 pr-4 text-right">
                            <button type="button" class="text-sm/5 font-medium text-primary-500 hover:text-primary-600">
                                Pas aan
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="h-32"></div>

    <div>
        <div
            class="w-160 divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-lg ring-1 ring-grey-200"
        >
            <div>count: {{ $results->count() }}</div>

            <div class="flex items-start justify-between px-4 py-4">
                <div class="flex items-start gap-3">
                    @foreach ($this->getFilters() as $filter)
                        {!! $filter->render() !!}
                    @endforeach
                </div>

                <button
                    type="button"
                    class="inline-flex gap-2 rounded-lg py-2 pl-3 pr-2 font-medium leading-5 text-grey-700 shadow ring-1 ring-grey-200"
                >
                    Bulk acties
                    <svg
                        class="h-5 w-5 text-grey-500"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>

            <table class="min-w-full table-fixed divide-y divide-grey-200">
                <thead class="text-grey-800">
                    <tr>
                        <th scope="col" class="w-5 py-2 pl-4 pr-3 text-left">
                            <div class="flex items-center">
                                <x-chief::input.checkbox name="all" id="all" />
                            </div>
                        </th>

                        <th scope="col" class="py-2 pl-3 pr-3 text-left font-medium">
                            <span class="leading-5">Titel</span>
                        </th>

                        <th scope="col" class="py-2 pl-3 pr-3 text-left font-medium">
                            <span class="leading-5">Id</span>
                        </th>

                        <th scope="col" class="py-2 pl-3 pr-4 text-left font-medium">
                            <span class="sr-only">Aanpassen</span>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-grey-200">
                    @foreach ($results as $model)
                        @php
                            $columns = $this->getRow($model);
                        @endphp

                        <tr wire:key="{{ $this->getRowKey($model) }}">
                            <td class="w-5 py-2 pl-4 pr-3 text-left">
                                <div class="flex items-center">
                                    <x-chief::input.checkbox
                                        name="{{ $this->getRowKey($model)  }}"
                                        id="{{ $this->getRowKey($model)  }}"
                                    />
                                </div>
                            </td>

                            @foreach ($columns as $column)
                                <td class="py-2 pl-3 pr-3 text-left">
                                    <span class="font-medium leading-5 text-grey-700">
                                        {{ $column }}
                                    </span>
                                </td>
                            @endforeach

                            <td class="py-2 pl-3 pr-4 text-right">
                                <button
                                    type="button"
                                    class="font-medium leading-5 text-primary-500 hover:text-primary-600"
                                >
                                    Aanpassen
                                </button>
                            </td>
                        </tr>

                        @if ($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable)
                            @foreach ($model->getChildren() as $childModel)
                                @php
                                    $columns = $this->getRow($childModel);
                                @endphp

                                <tr wire:key="{{ $this->getRowKey($childModel) }}">
                                    <td class="w-5 py-2 pl-4 pr-3 text-left">
                                        <div class="flex items-center">
                                            <x-chief::input.checkbox
                                                name="{{ $this->getRowKey($childModel)  }}"
                                                id="{{ $this->getRowKey($childModel)  }}"
                                            />
                                        </div>

                                        <div style="width: 20px" class="flex items-end">
                                            <svg
                                                class="h-5 w-5 text-grey-300"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor"
                                                viewBox="0 0 256 256"
                                            >
                                                <path
                                                    d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"
                                                ></path>
                                            </svg>
                                        </div>
                                    </td>

                                    @foreach ($columns as $column)
                                        <td class="py-2 pl-3 pr-3 text-left">
                                            <span class="font-medium leading-5 text-grey-700">
                                                {{ $column }}
                                            </span>
                                        </td>
                                    @endforeach

                                    <td class="py-2 pl-3 pr-4 text-right">
                                        <button
                                            type="button"
                                            class="font-medium leading-5 text-primary-500 hover:text-primary-600"
                                        >
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
                    {{ $results->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

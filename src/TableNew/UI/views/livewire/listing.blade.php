<div>

    {{--    <input type="text" wire:model.live="filters.title">--}}
        @foreach($this->getFilters() as $filter)
            {!! $filter->render() !!}
        @endforeach

    <button type="button" wire:click="download">
        Download Invoice
    </button>


    <table>
        <thead>
        <tr>
            <th>Column titles</th>
        </tr>
        </thead>
        <tbody>
            @foreach($this->getModels() as $model)
                @php
                    $row = $this->getRow($model);
                @endphp
                <tr wire:key="{{ $this->getRowKey($model)  }}">
                    @foreach($row as $column)
                        <td>{{$column}}</td>
                    @endforeach
                </tr>

{{--                @if($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable)--}}
{{--                    @foreach($model->getChildren() as $childModel)--}}
{{--                        @php--}}
{{--                            $row = $this->getRow($childModel);--}}
{{--                        @endphp--}}
{{--                        <tr wire:key="{{ $this->getRowKey($childModel)  }}">--}}
{{--                            @foreach($row as $column)--}}
{{--                                <td>--> {{$column}}</td>--}}
{{--                            @endforeach--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
            @endforeach
        </tbody>
        <tfoot>
        pagination?
        </tfoot>
    </table>
</div>


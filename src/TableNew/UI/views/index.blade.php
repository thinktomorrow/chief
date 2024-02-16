ACTIES
BULKACTIES (context)
ROWACTIES
FILTER
SORT

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

<table>
    <thead>
        <tr>
            <th>Column titles</th>
        </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{$row->id}}</td>
            <td>{{$row->title}} {{$row->id}}</td>
        </tr>

        @if(isset($row->rows))
            @foreach($row->rows as $childRow)
                <tr>
                    <td>-> {{$childRow->id}}</td>
                    <td>{{$childRow->title}} {{$childRow->id}}</td>
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
    <tfoot>
    pagination?
    </tfoot>
</table>

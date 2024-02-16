<?php

namespace Thinktomorrow\Chief\TableNew\UI\Livewire;

use Thinktomorrow\Chief\TableNew\UI\Helpers\TableColumn;

class ArticleListing extends Listing
{
    public function getRows(): iterable
    {
        return json_decode(json_encode([
            ['id' => 5, 'title' => 'new kid on the block'],
            ['id' => 6, 'title' => 'new kid on the block'],
            ['id' => 8, 'title' => 'new kid on the block', 'rows' => json_decode(json_encode([
                ['id' => 45, 'title' => 'new kid on the block'],
                ['id' => 77, 'title' => 'new kid on the blodfqdf'],
            ]))],
        ]));
    }

    public function getRow($model): iterable
    {
        return [
            $model->id,
            $model->title,
//            TableColumn::image($model->asset('image')->url('thumb')),
        ];
    }
}

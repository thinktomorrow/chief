<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

use Illuminate\Database\Eloquent\Model;

final class MemoizedEloquentOptions
{
    private array $options = [];

    /**
     * @return array<int|string, mixed>
     */
    public function getOptions(Model $model, string $valueKey, string $labelKey): array
    {
        $key = serialize([
            $model::class,
            $model->getConnection()->getName(),
            $model->getTable(),
            $valueKey,
            $labelKey,
            app()->getLocale(),
        ]);

        return $this->options[$key] ??= $model->newQuery()
            ->get()
            ->pluck($labelKey, $valueKey)
            ->toArray();
    }
}

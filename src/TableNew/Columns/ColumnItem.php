<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Columns;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasColumnName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\Table\Concerns\HasHint;
use Thinktomorrow\Chief\Table\Concerns\HasView;
use Thinktomorrow\Chief\TableNew\Concerns\CanEvaluateValue;
use Thinktomorrow\Chief\TableNew\Concerns\HasLink;
use Thinktomorrow\Chief\TableNew\Concerns\HasMultipleValues;
use Thinktomorrow\Chief\TableNew\Concerns\HasTeaser;
use Thinktomorrow\Chief\TableNew\Concerns\HasValueMap;

abstract class ColumnItem extends \Illuminate\View\Component implements Htmlable
{
    use HasComponentRendering;
    use HasView;
    use HasCustomAttributes;
    use HasKey;
    use HasTitle;
    use HasDescription;

    use HasModel;
    use HasColumnName;
    use HasDefault;
    use HasLocalizableProperties;
    use HasTeaser;
    use HasValue{
        getValue as getDefaultValue;
    }
    use HasMultipleValues;
    use HasValueMap;
    use HasLink;

    public function __construct(string $key)
    {
        $this->key($key);
        $this->title($key);

        // If we detect the key contains a dot, we assume it's a relationship key.
        if(strpos($key, '.') !== false) {
            $this->columnName(substr($key, 0, strpos($key, '.')));
            $this->eachValue(function($value) use($key) {
                $key = substr($key, strpos($key, '.') + 1);
                return $value ? $value->{$key} : null;
            });
        } else {
            $this->columnName($key);
        }
    }

    public static function make(string|int|null $key)
    {
        return new static((string) $key);
    }

    public function render(): View
    {
        $view = $this->getView();

        return view($view, array_merge($this->data(), [
            'component' => $this,
        ]));
    }
}

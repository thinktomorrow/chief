<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Fields\Types\SelectField;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class PageField extends SelectField
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::PAGE), $key);
    }

    public function options(array $morphKeys = [])
    {
        $morphKeys = collect($morphKeys)->map(function($key){
            return (new $key)->getMorphClass();
        });
        
        if( ! $morphKeys->isEmpty()) {
            $pages = Page::whereIn('morph_key', $morphKeys)->get();
        } else {
            $pages = Page::all();
        }

        $this->values['options'] = FlatReferencePresenter::toGroupedSelectValues($pages)->toArray();

        return $this->grouped();
    }
}

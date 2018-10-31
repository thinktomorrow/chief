<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\SelectField;
use Thinktomorrow\Chief\Pages\PageManager;

class ArticlePageWithCategoriesManager extends PageManager
{
    public function fields(): Fields
    {
        return new Fields([
            InputField::make('custom'),
            SelectField::make('categories')->options(Category::all()->pluck('title', 'id')->toArray()),
        ]);
    }

    public function saveCategoriesField($field, $request)
    {
        $this->model->categories()->sync($request->get('categories', []));
    }
}

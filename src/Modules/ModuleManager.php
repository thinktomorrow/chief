<?php

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\PagebuilderField;
use Thinktomorrow\Chief\Fields\Types\TextField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\ModelManager;

class ModuleManager extends AbstractManager implements ModelManager
{
    /**
     * The set of fields that should be manageable for a certain model.
     *
     * Additionally, you should:
     * 1. Make sure to setup the proper migrations and
     * 2. For a translatable field you should add this field to the $translatedAttributes property of the model as well.
     *
     * @return Fields
     */
    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title')->translatable($this->model->availableLocales()),
            HtmlField::make('content')->translatable($this->model->availableLocales()),
        ]);
    }
}
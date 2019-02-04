<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\FieldsTab;
use Thinktomorrow\Chief\Management\ManagementDefaults;

class ManagerFakeWithFieldTabs extends ManagerFake
{
    public function fieldArrangement($key = null): FieldArrangement
    {
        return new FieldArrangement($this->fields(), [
            new FieldsTab('Main', ['title', 'custom', 'avatar']),
            new FieldsTab('translations', ['title_trans', 'content_trans']),
        ]);
    }
}

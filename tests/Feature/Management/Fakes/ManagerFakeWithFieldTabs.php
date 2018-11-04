<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\FieldTab;
use Thinktomorrow\Chief\Management\ManagementDefaults;

class ManagerFakeWithFieldTabs extends ManagerFake
{
    public function fieldArrangement(): FieldArrangement
    {
        return new FieldArrangement($this->fields(), [
            new FieldTab('Main', ['title', 'custom', 'avatar']),
            new FieldTab('translations', ['title_trans', 'content_trans']),
        ]);
    }
}

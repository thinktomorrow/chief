<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Management\Fields\FieldArrangement;
use Thinktomorrow\Chief\Management\Fields\FieldTab;
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
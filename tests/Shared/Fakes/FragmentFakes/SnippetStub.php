<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Fragments\Assistants\StaticFragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;

class SnippetStub implements Fragmentable
{
    use StaticFragmentableDefaults;

//    public function renderAdminFragment($owner, $loop)
//    {
//        // TODO: Implement renderAdminFragment() method.
//    }
//
//    public function renderFragment($owner, $loop, $viewData = []): string
//    {
//        return 'snippet-stub';
//    }

    public static function managedModelKey(): string
    {
        return 'snippet-stub';
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title'),
        ]);
    }

    public function getTitle()
    {
        return $this->fragmentModel->title;
    }
}

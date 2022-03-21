<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Managers\Manager;

class Image extends File
{
    protected string $view = 'chief-form::fields.image';
    protected string $windowView = 'chief-form::fields.image-window';

    public function fill(Manager $manager, Model $model): void
    {
        $this->endpoint($manager->route('asyncUploadSlimImage', $this->getKey(), $this->getModel()?->id));
    }
}

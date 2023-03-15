<?php

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class Bookmark extends Component implements Field
{
    protected string $view = 'chief-form::fields.bookmark';
    protected string $windowView = 'chief-form::fields.text';

    /** @var array The frontend url of the current owner page. Used to show preview url link in admin. Array of locales */
    private array $ownerUrls = [];

    public function fill(Manager $manager, Model $model): void
    {
        if (! $model instanceof Visitable) {
            return;
        }

        $ownerUrls = [];

        foreach (config('chief.locales') as $locale) {
            $ownerUrls[$locale] = $model->url($locale);
        }

        $this->ownerUrls($ownerUrls);
    }

    public function ownerUrls(array $ownerUrls): static
    {
        $this->ownerUrls = $ownerUrls;

        return $this;
    }

    public function getOwnerUrl(?string $locale = null): ?string
    {
        return $this->ownerUrls[($locale ?? app()->getLocale())] ?? null;
    }
}

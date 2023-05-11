<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\ValidationRules\UniqueUrlSlugRule;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class PropagateUrlChange
{
    private array $locales;
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->locales = config('chief.locales', []);
        $this->registry = $registry;
    }

    /**
     * When a nestable url gets saved, we'll make sure that all
     * underlying children will have their urls updated.
     */
    public function handle(Nestable & Visitable $model): void
    {
        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {
            $this->resaveUrlSlug($model, $locale);
        }

        foreach ($model->getChildren() as $child) {
            $this->handle($child);
        }
    }

    public function onManagedModelUrlUpdated(ManagedModelUrlUpdated $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof Nestable) {
            return;
        }

        $this->handle($model);
    }

    /**
     * This will retrigger the save for a nested page, which will now
     * take the updated parent slug as its base url segment.
     */
    private function resaveUrlSlug(Nestable & Visitable $model, string $locale): void
    {
        $currentSlug = UrlRecord::findSlugByModel($model, $locale);

        $strippedSlug = false != strpos($currentSlug, '/') ? substr($currentSlug, strrpos($currentSlug, '/') + 1) : $currentSlug;

        // Avoid saving the new slug in case that this slug already exists on another model
        if (! (new UniqueUrlSlugRule($model, $model))->passes(null, [$locale => $strippedSlug])) {
            return;
        }

        (new SaveUrlSlugs())->handle($model, [$locale => $strippedSlug]);
    }
}

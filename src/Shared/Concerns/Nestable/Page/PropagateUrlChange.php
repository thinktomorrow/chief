<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Site\Urls\Application\ResaveUrlSlug;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class PropagateUrlChange
{
    private array $locales;
    private Registry $registry;
    private ResaveUrlSlug $resaveUrlSlug;

    public function __construct(Registry $registry, ResaveUrlSlug $resaveUrlSlug)
    {
        $this->locales = ChiefSites::getLocales();
        $this->registry = $registry;
        $this->resaveUrlSlug = $resaveUrlSlug;
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
     * When a nestable url gets saved, we'll make sure that all
     * underlying children will have their urls updated.
     */
    public function handle(Nestable & Visitable $model): void
    {
        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {
            $this->resaveUrlSlug->handle($model, $locale);
        }

        foreach ($model->getChildren() as $child) {
            $this->handle($child);
        }
    }
}

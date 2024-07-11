<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Site\Urls\Application\ResaveUrlSlug;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class PropagateUrlChange
{
    private array $locales;
    private Registry $registry;
    private ResaveUrlSlug $resaveUrlSlug;

    public function __construct(Registry $registry, ResaveUrlSlug $resaveUrlSlug)
    {
        $this->locales = config('chief.locales', []);
        $this->registry = $registry;
        $this->resaveUrlSlug = $resaveUrlSlug;
    }

    /**
     * When a nestable url gets saved, we'll make sure that all
     * underlying children will have their urls updated.
     */
    public function handle(Nestable & Visitable $model, ?Model $formerParent = null): void
    {
        $parentModel = $model->getParent();

        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {

            $formerParentSlugs = [];

            if($formerParent) {
                $formerParentSlugs[] = UrlRecord::findRecentRedirect($formerParent, $locale)?->slug;
            }

            if($parentModel) {
                $formerParentSlugs[] = UrlRecord::findRecentRedirect($parentModel, $locale)?->slug;
            }

            $this->resaveUrlSlug->handle($model, $locale, $formerParentSlugs);
        }

        foreach ($model->getChildren() as $child) {
            $this->handle($child, );
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
}

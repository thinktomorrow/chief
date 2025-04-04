<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Site\Urls\Application\ResaveUrlSlug;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;

class PropagateUrlChange
{
    private array $locales;

    private Registry $registry;

    private ResaveUrlSlug $resaveUrlSlug;

    public function __construct(Registry $registry, ResaveUrlSlug $resaveUrlSlug)
    {
        $this->locales = ChiefSites::locales();
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
    public function handle(Nestable&Visitable $model, ?Model $formerParent = null): void
    {
        $parentModel = $model->getParent();

        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {

            $strippableSlugs = [];

            // In case of a parent switch, allow to replace the base url segment belonging to a former parent.
            if ($formerParent) {
                $strippableSlugs[] = UrlRecord::findSlugByModel($formerParent, $locale);
            }

            // In case of parent changing its url, allow to replace the former base url segment belonging the parent.
            if ($parentModel) {
                $strippableSlugs[] = UrlRecord::findSlugByModel($parentModel, $locale);
                $strippableSlugs[] = UrlRecord::findRecentRedirect($parentModel, $locale)?->slug;
            }

            $this->resaveUrlSlug->handle($model, $locale, $strippableSlugs);
        }

        foreach ($model->getChildren() as $child) {
            $this->handle($child);
        }
    }
}

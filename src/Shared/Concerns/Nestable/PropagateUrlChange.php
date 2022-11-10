<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableRepository;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
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
    public function handle(NestedNode $node): void
    {
        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {
            $this->resaveUrlSlug($node, $node->getModel(), $locale);

            foreach ($node->getChildNodes() as $child) {
                $this->handle($child);
            }
        }
    }

    public function onManagedModelUrlUpdated(ManagedModelUrlUpdated $event): void
    {
        $resource = $this->registry->findResourceByModel($event->modelReference->className());
        dd($resource);
        $model = $event->modelReference->instance();

        // TODO: get resource of model
        // TODO: check if nestable

        $node = $model->nestableRepository()->findNestableById($model->getKey());

        $this->handle($node);
    }

    /**
     * This will retrigger the save for a nested page, which will now
     * take the updated parent slug as its base url segment.
     */
    private function resaveUrlSlug(NestedNode $node, Visitable $model, string $locale): void
    {
        $currentSlug = $node->getUrlSlug($locale) ?: '';

        $strippedSlug = false != strpos($currentSlug, '/') ? substr($currentSlug, strrpos($currentSlug, '/') + 1) : $currentSlug;

        (new SaveUrlSlugs())->handle($model, [$locale => $strippedSlug]);
    }
}

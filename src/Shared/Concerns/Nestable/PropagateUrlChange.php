<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableRepository;

class PropagateUrlChange
{
    private array $locales;
    private NestableRepository $nestableRepository;

    public function __construct(NestableRepository $nestableRepository)
    {
        $this->locales = config('chief.locales', []);
        $this->nestableRepository = $nestableRepository;
    }

    /**
     * When a nestable url gets saved, we'll make sure that all
     * underlying children will have their urls updated.
     */
    public function handle(Visitable $model): void
    {
        $node = $this->nestableRepository->findNestableById($model->getKey());

        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {
            $this->resaveUrlSlug($node, $model, $locale);

            foreach ($node->getChildNodes() as $child) {
                $this->handle($child->getModel());
            }
        }
    }

    public function onManagedModelUrlUpdated(ManagedModelUrlUpdated $event): void
    {
        $model = $event->modelReference->instance();

        dd($model);
        // If nestable
        $this->handle($model);
    }

    /**
     * This will retrigger the save for a nested page, which will now
     * take the updated parent slug as its base url segment.
     */
    private function resaveUrlSlug(NestedNode $node, Visitable $model, string $locale): void
    {
//        $currentSlug = ($urlRecord = UrlRecord::findByModel($model, $locale)) ? $urlRecord->slug : '';
        $currentSlug = $node->getUrlSlug($locale) ?: '';

        $strippedSlug = false != strpos($currentSlug, '/') ? substr($currentSlug, strrpos($currentSlug, '/') + 1) : $currentSlug;

        (new SaveUrlSlugs())->handle($model, [$locale => $strippedSlug]);
    }

//    protected function getCurrentSlug(): string
//    {
//        // TODO: for each locales... not only NL
//        return ($urlRecord = UrlRecord::findByModel($this, 'nl')) ? $urlRecord->slug : '';
//    }
}

<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Templates;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Webmozart\Assert\Assert;

class PageTemplateApplicator implements TemplateApplicator
{
    /** @var ModuleTemplateApplicator */
    private $moduleTemplateApplicator;

    public function __construct(ModuleTemplateApplicator $moduleTemplateApplicator)
    {
        $this->moduleTemplateApplicator = $moduleTemplateApplicator;
    }

    public function handle($sourceModel, $targetModel): void
    {
        /** @var Page $sourceModel */
        Assert::isInstanceOf($sourceModel, Page::class);

        if ($this->shouldApplyRelations($sourceModel, $targetModel))
        {
            $this->applyRelations($sourceModel, $targetModel);
        }


        // which parts to provide?
        //    public function children(): Collection;
        //    public function values(): array;
        //    public function translations(): array;
        //    public function fragments(): array;
    }

    public function shouldApply($sourceModel, $targetModel): bool
    {
        return ($sourceModel instanceof Page);
    }

    private function shouldApplyRelations($sourceModel, $targetModel): bool
    {
        return ($sourceModel instanceof ActsAsParent && $targetModel instanceof ActsAsParent);
    }

    private function applyRelations(ActsAsParent $sourceModel, ActsAsParent $targetModel): void
    {
        foreach ($sourceModel->children() as $child)
        {
            $duplicatedChild = null;

            if ($child instanceof Module && $child->isPageSpecific())
            {
                $duplicatedChild = $child::create([
                    'slug'           => $targetModel->title ? $targetModel->title . '-' . $child->slug : $child->slug . '-copy',
                    'owner_id'        => $targetModel->id,
                    'owner_type' => $targetModel->getMorphClass()
                ]);

                $this->moduleTemplateApplicator->handle($child, $duplicatedChild);
            } else
            {
                $duplicatedChild = $child;
            }

            $targetModel->adoptChild($duplicatedChild, ['sort' => $child->relation->sort]);
        }
    }
}

<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Templates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Webmozart\Assert\Assert;

class ApplyTemplate
{
    /** @var array */
    private $applicators;

    public function __construct(array $applicators)
    {
        Assert::allIsInstanceOf($applicators, TemplateApplicator::class);

        $this->applicators = $applicators;
    }

    public function handle(string $sourceClassName, string $sourceId, string $targetClassName, string $targetId): void
    {
        $sourceModel = $this->findModel($sourceClassName, $sourceId);
        $targetModel = $this->findModel($targetClassName, $targetId);

        /** @var TemplateApplicator $applicator */
        foreach($this->applicators as $applicator) {
            if(!$applicator->shouldApply($sourceModel, $targetModel)) {
                continue;
            }

            $applicator->handle($sourceModel, $targetModel);

            return;
        }

        $availableApplicatorsString = implode(array_map(function($applicator){ return get_class($applicator);}, $this->applicators), ',');

        throw new \RuntimeException("No proper template applicator found. $sourceClassName [$sourceId] cannot be applied as template. Available applicators: [$availableApplicatorsString]");
    }

    /**
     * For now we assume that each class references an Eloquent Model.
     *
     * @param string $className
     * @param string $sourceId
     */
    private function findModel(string $className, string $sourceId): Model
    {
        if($morphedClassName = Relation::getMorphedModel($className)) {
            $className = $morphedClassName;
        }

        return (new $className)->findOrFail($sourceId);
    }
}

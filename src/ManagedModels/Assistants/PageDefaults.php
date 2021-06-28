<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\ManagedModels\States\UsesPageState;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use ManagedModelDefaults;
    use Viewable;
    use VisitableDefaults;
    use OwningFragments;

    use UsesPageState;
    use Publishable;
    use Archivable;

    use AssetTrait;
    use HasDynamicAttributes;

    /** @var array */
    private $extractedFieldKeys;

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        return config('chief.locales');
    }

    /**
     * As a default, we'll guess the dynamic keys based on the provided fields. This should give you a
     * nice and clean setup. Should you need to customize the dynamic keys, you'll be able to define
     * a dynamicKeys property on the model. This will circumvent the logic below.
     *
     * @return array
     */
    protected function dynamicKeys(): array
    {
        if (property_exists($this, 'dynamicKeys')) {
            return $this->dynamicKeys;
        }

        if ($this->extractedFieldKeys) {
            return $this->extractedFieldKeys;
        }

        return $this->extractedFieldKeys = $this->extractFieldKeys();
    }

    /**
     * This automatically extract the field keys from the Field definitions.
     *
     * TODO: At the moment the field keys are used as the field dynamic keys. Better is to use the field::getColumn values instead.
     * in case there is an explicit column set different from the key, this is currently not detected yet.
     *
     * @return array
     * @throws \ReflectionException
     */
    private function extractFieldKeys(): array
    {
        $refMethod = new \ReflectionMethod($this, 'fields');
        $iterator = new \LimitIterator(new \SplFileObject($refMethod->getFileName()), $refMethod->getStartLine(), $refMethod->getEndLine() - $refMethod->getStartLine());

        return collect(iterator_to_array($iterator))->filter(function ($line) {
            return false !== stripos($line, '@dynamicKeys') || false !== strpos($line, '::make(');
        })->map(function ($line) {

            // Search for @dynamicKeys line
            preg_match('#@dynamicKeys: ([^\*]*)#i', $line, $matches);
            if (count($matches) > 0) {
                return array_map(fn ($item) => trim($item), explode(',', $matches[1]));
            }

            // Search for Field::make line
            preg_match('@make\(\'([^\']*)\'\)@', $line, $matches);

            return (count($matches) < 2) ? null : $matches[1];
        })->flatten()
            ->unique()
            ->reject(fn ($value) => is_null($value))
            ->values()
            ->toArray();
    }
}

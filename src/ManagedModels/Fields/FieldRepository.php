<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\Field;
use Thinktomorrow\Chief\Legacy\Fragments\FragmentField;
use Thinktomorrow\Chief\Management\Managers;

class FieldRepository
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function find(FieldReference $reference): Field
    {
        $manager = $this->managers->findByKey($reference->getManagerKey());

        $field = $manager->fields()[($reference->hasFragmentKey() ? $reference->getFragmentKey() : $reference->getFieldKey())];

        if ($reference->hasFragmentKey()) {
            if (!$field instanceof FragmentField) {
                throw new \RuntimeException('Field ' . $field->getKey() . ' was expected to be a fragmentfield but its not.');
            }

            $firstFragment = $field->getFragments()[0];

            return $firstFragment->getFields()[$reference->getFieldKey()];
        }

        return $field;
    }
}

<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use InvalidArgumentException;
use Thinktomorrow\Chief\Forms\Concerns\HasFields;
use Thinktomorrow\Chief\Forms\Fields\Common\ResolveIterables;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Layouts\Concerns\SetsScopedLocales;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;

class Layout implements HasTaggedComponents
{
    use HasFields;
    use HasModel;
    use SetsScopedLocales;
    use WithTaggedComponents;

    final private function __construct(array $components)
    {
        $this->assertUniqueIds($components);

        $this->components = $components;
    }

    public static function make(iterable $generator = []): self
    {
        $self = new static([]);
        $createdForm = null;

        foreach (ResolveIterables::resolve($generator) as $i => $component) {

            /** Bundle wandering fields together into a Form Component */
            if ($component instanceof Field) {

                if (! $createdForm) {
                    $self = $self->add(
                        $createdForm = Form::make('form_'.$i)
                    );
                }

                $createdForm->addComponent($component);

                continue;
            }

            self::protectAgainstNestedForms($component);

            $self = $self->add($component);
        }

        return $self;
    }

    /** Check if there aren't any nested forms - which is not supported */
    private static function protectAgainstNestedForms($component): void
    {
        if ($component instanceof Form) {
            foreach ($component->getComponents() as $nestedComponent) {
                if ($nestedComponent instanceof Form) {
                    throw new InvalidArgumentException('Nested forms are not supported. Nested form ['.$nestedComponent->getKey().'] found inside Form ['.$component->getKey().'].');
                }
            }
        }
    }

    public function hasForm(string $formId): bool
    {
        foreach ($this->components as $form) {
            if ($form->getId() == $formId) {
                return true;
            }
        }

        return false;
    }

    public function findForm(string $formId): Form
    {
        foreach ($this->components as $form) {
            if ($form->getId() == $formId) {
                return $form;
            }
        }

        throw new InvalidArgumentException('No Form found by id: '.$formId);
    }

    public function filterByPosition(string $position): static
    {
        return $this
//            ->exclude(['pagetitle'])
            ->filter(fn ($component) => $component->getPosition() == $position);
    }

    public function exclude(array|string $componentKeys): static
    {
        if (! is_array($componentKeys)) {
            $componentKeys = [$componentKeys];
        }

        return $this->filter(fn ($component) => ! in_array($component->getKey(), $componentKeys));
    }

    private function filter(callable $filter): static
    {
        $forms = [];

        foreach ($this->components as $component) {
            if ($filter($component) == true) {
                $forms[] = $component;
            }
        }

        return new static($forms);
    }

    private function add($component): self
    {
        return new static(array_merge($this->components, [$component]));
    }

    private function assertUniqueIds(array $components): void
    {
        $ids = [];

        /** @var Form $component */
        foreach ($components as $component) {
            if (in_array($component->getId(), $ids)) {
                throw new InvalidArgumentException('Form ids should be unique. Form id ['.$component->getId().'] is used more than once.');
            }

            $ids[] = $component->getId();
        }
    }
}

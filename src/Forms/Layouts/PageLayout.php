<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use InvalidArgumentException;
use Thinktomorrow\Chief\Forms\Concerns\HasFields;
use Thinktomorrow\Chief\Forms\Fields\Common\ResolveIterables;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Layouts\Concerns\WithLocalizedFields;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;

class PageLayout implements HasTaggedComponents
{
    use HasFields;
    use HasModel;
    use WithLocalizedFields;
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
            if (! $component instanceof Layout) {

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
            ->filter(fn ($component) => $component->getPosition() == $position);
    }

    /**
     * All components that are positioned in the 'main' (default) position.
     */
    public function filterByDefaultPosition(): static
    {
        return $this
            ->filter(fn (Layout $component) => ! $component->getPosition() || $component->getPosition() == 'main');
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
        $components = [];

        foreach ($this->components as $component) {
            if ($filter($component) == true) {
                $components[] = $component;
            }
        }

        return new static($components);
    }

    private function add($component): self
    {
        return new static(array_merge($this->components, [$component]));
    }

    private function assertUniqueIds(array $components): void
    {
        $keys = [];

        /** @var Layout $component */
        foreach ($components as $component) {
            if (in_array($component->getKey(), $keys)) {
                throw new InvalidArgumentException('Component keys should be unique. Component key ['.$component->getKey().'] is used more than once.');
            }

            $keys[] = $component->getKey();
        }
    }
}

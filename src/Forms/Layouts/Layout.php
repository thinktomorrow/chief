<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use InvalidArgumentException;
use Thinktomorrow\Chief\Forms\Concerns\HasFields;
use Thinktomorrow\Chief\Forms\Fields\Common\ResolveIterables;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;

class Layout implements HasTaggedComponents
{
    use HasFields;
    use HasModel;
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

            $self = $self->add($component);
        }

        return $self;
    }

    //    private function eachForm(callable $logic): self
    //    {
    //        foreach ($this->components as $form) {
    //            call_user_func($logic, $form);
    //        }
    //
    //        return $this;
    //    }
    //
    //    public function fillModel(Model $model): self
    //    {
    //        return $this->eachForm(fn ($form) => $form->fillModel($model));
    //    }
    //
    //    /**
    //     * Default form composition with model prefills and action urls.
    //     */
    //    public function fill(Manager $manager, Model $model): self
    //    {
    //        return $this->eachForm(fn ($form) => $form->fill($manager, $model));
    //    }
    //
    //    public function fillFields(Manager $manager, Model $model): self
    //    {
    //        return $this->eachForm(fn ($form) => $form->fillFields($manager, $model));
    //    }

    //    public function get()
    //    {
    //        return $this->components;
    //    }

    //    public function has(string $formId): bool
    //    {
    //        foreach ($this->components as $form) {
    //            if ($form->getId() == $formId) {
    //                return true;
    //            }
    //        }
    //
    //        return false;
    //    }

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

<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Managers\Manager;

class Forms
{
    use HasComponents;
    use HasFields;

    final private function __construct(array $components)
    {
        $this->assertUniqueIds($components);

        $this->components = $components;
    }

    public static function make(iterable $generator = []): self
    {
        $forms = new static([]);
        $createdForm = null;

        foreach (static::flattenIterable($generator) as $i => $form) {
            if ($form instanceof Form) {
                $forms = $forms->add($form);

                continue;
            }

            if (! $createdForm) {
                $forms = $forms->add(
                    $createdForm = Form::make('form_'.$i)
                );
            }

            $createdForm->addComponent($form);
        }

        return $forms;
    }

    public function eachForm(callable $logic): self
    {
        foreach ($this->components as $form) {
            call_user_func($logic, $form);
        }

        return $this;
    }

    public function fillModel(Model $model): self
    {
        return $this->eachForm(fn ($form) => $form->fillModel($model));
    }

    /**
     * Default form composition with model prefills and action urls.
     */
    public function fill(Manager $manager, Model $model): self
    {
        return $this->eachForm(fn ($form) => $form->fill($manager, $model));
    }

    public function fillFields(Manager $manager, Model $model): self
    {
        return $this->eachForm(fn ($form) => $form->fillFields($manager, $model));
    }

    public function get()
    {
        return $this->components;
    }

    public function has(string $formId): bool
    {
        foreach ($this->components as $form) {
            if ($form->getId() == $formId) {
                return true;
            }
        }

        return false;
    }

    public function find(string $formId): Form
    {
        foreach ($this->components as $form) {
            if ($form->getId() == $formId) {
                return $form;
            }
        }

        throw new InvalidArgumentException('No Form found by id: '.$formId);
    }

    public function exclude(array $formIds): static
    {
        return $this->filter(fn($form) => !in_array($form->getId(),$formIds));
    }

    private function filter(callable $filter): static
    {
        $forms = [];

        foreach ($this->components as $form) {
            if (true == $filter($form)) {
                $forms[] = $form;
            }
        }

        return new static($forms);
    }

    /**
     * First expand all fields so that any generators are resolved as well.
     */
    private static function flattenIterable(iterable $iterable): iterable
    {
        $flattened = null;

        foreach ($iterable as $entry) {
            if (is_iterable($entry)) {
                foreach ($entry as $_entry) {
                    $flattened[] = $_entry;
                }
            } else {
                $flattened[] = $entry;
            }
        }

        return $flattened;
    }

    private function add(Form $form): self
    {
        return new static(array_merge($this->components, [$form]));
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

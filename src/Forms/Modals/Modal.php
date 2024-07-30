<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Modals;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Concerns\HasLayout;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\HasFields;
use Thinktomorrow\Chief\Forms\Layouts\Component;
use Thinktomorrow\Chief\Forms\Modals\Concerns\HasButton;
use Thinktomorrow\Chief\Forms\Modals\Concerns\HasContent;
use Thinktomorrow\Chief\Forms\Modals\Concerns\HasSubTitle;

class Modal extends Component implements Wireable
{
    use HasModel;
    use HasFields;
    use HasElementId;
    use HasLayout;
    use HasSubTitle;
    use HasContent;
    use HasButton;

    protected string $view = 'chief-form::modals.modal';

    public function __construct(string $id = 'modal')
    {
        parent::__construct($id);

        $this->elementId($this->id . '_' . Str::random());
    }

    public function toLivewire()
    {
        return $this->toArray();
    }

    public static function fromLivewire($value)
    {
        return static::fromArray($value);
    }

    private static function fromArray(array $values): self
    {
        $modal = new static($values['id']);
        if (isset($values['title'])) {
            $modal->title($values['title']);
        }
        if (isset($values['subTitle'])) {
            $modal->subTitle($values['subTitle']);
        }
        if (isset($values['content'])) {
            $modal->content($values['content']);
        }
        if ($values['form']) {
            $modal->form($values['form']['class']::fromLivewire($values['form']));
        }
        //        if($values['form']) $modal->fields(collect($values['fields'])->map(fn ($field) => $field['class']::fromLivewire($field)));
        if ($values['button']) {
            $modal->button($values['button']);
        }
        $modal->elementId($values['elementId']);

        return $modal;
    }

    private function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subTitle' => $this->subTitle,
            'content' => $this->content,
            'form' => $this->form->toLivewire(),
            'button' => $this->button->toArray(),
            'layout' => $this->layout->toArray(),
            'elementId' => $this->elementId,
        ];
    }

    /**
     * Shorthand for setting the fields of the modal form.
     * @param $fields
     * @return $this
     */
    public function form($fields): static
    {
        return $this->items($fields);
    }
}

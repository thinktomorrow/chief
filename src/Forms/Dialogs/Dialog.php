<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Concerns\HasLayout;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasButton;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasContent;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasDialogType;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasSubTitle;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\HasFields;
use Thinktomorrow\Chief\Forms\Layouts\Component;

class Dialog extends Component implements Wireable
{
    use HasDialogType;
    use HasModel;
    use HasFields;
    use HasElementId;
    use HasLayout;
    use HasSubTitle;
    use HasContent;
    use HasButton;

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
        $dialog = new static($values['id']);

        $dialog->asType(DialogType::from($values['dialogType']));

        if (isset($values['title'])) {
            $dialog->title($values['title']);
        }
        if (isset($values['subTitle'])) {
            $dialog->subTitle($values['subTitle']);
        }
        if (isset($values['content'])) {
            $dialog->content($values['content']);
        }
        if ($values['form']) {
            $dialog->form($values['form']['class']::fromLivewire($values['form']));
        }
        //        if($values['form']) $modal->fields(collect($values['fields'])->map(fn ($field) => $field['class']::fromLivewire($field)));
        if ($values['button']) {
            $dialog->button($values['button']);
        }
        $dialog->elementId($values['elementId']);

        return $dialog;
    }

    private function toArray()
    {
        return [
            'id' => $this->id,
            'dialogType' => $this->dialogType->value,
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

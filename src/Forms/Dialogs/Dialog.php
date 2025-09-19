<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Concerns\HasFields;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasButton;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasContent;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasDialogType;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasSubTitle;
use Thinktomorrow\Chief\Forms\Layouts\LayoutComponent;

class Dialog extends LayoutComponent
{
    use HasButton;
    use HasContent;
    use HasDialogType;
    use HasElementId;
    use HasFields;
    use HasSubTitle;
    use HasTitle;

    public function __construct(string $key = 'modal')
    {
        parent::__construct($key);

        $this->elementId($this->id.'_'.Str::random());
    }

    /**
     * Shorthand for setting the fields of the modal form.
     *
     * @return $this
     */
    public function form($fields): static
    {
        return $this->items($fields);
    }
}

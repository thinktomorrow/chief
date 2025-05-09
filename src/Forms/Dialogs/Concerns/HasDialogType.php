<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\DialogType;

trait HasDialogType
{
    protected DialogType $dialogType = DialogType::modal;

    public function asModal(): self
    {
        return $this->asType(DialogType::modal);
    }

    public function asDrawer(): self
    {
        return $this->asType(DialogType::drawer);
    }

    private function asType(DialogType $dialogType): self
    {
        $this->dialogType = $dialogType;

        return $this;
    }

    public function getType(): DialogType
    {
        return $this->dialogType;
    }
}

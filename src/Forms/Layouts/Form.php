<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasFields;
use Thinktomorrow\Chief\Forms\Layouts\Concerns\HasFormDisplay;
use Thinktomorrow\Chief\Forms\Layouts\Concerns\SetsScopedLocales;

class Form extends Component
{
    use HasFields;
    use HasFormDisplay;
    use SetsScopedLocales;

    protected string $view = 'chief-form::layouts.form';

    protected string $viewInline = 'chief-form::layouts.form-inline';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->position('main');
        $this->tag($key);
    }

    public function getView(): string
    {
        if ($this->getFormDisplay() === 'inline') {
            return $this->viewInline;
        }

        return $this->view;
    }

    protected function wireableMethods(array $components): array
    {
        return array_merge(parent::wireableMethods($components), [
            ...(isset($this->formDisplay) ? ['setFormDisplay' => $this->formDisplay] : []),
        ]);
    }
}

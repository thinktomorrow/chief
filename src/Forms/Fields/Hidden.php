<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Hidden extends Component implements Field
{
    protected string $view = 'chief-form::fields.hidden';

    protected string $previewView = 'chief-form::previews.fields.hidden';
}

<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Nav;

class BreadCrumb
{
    public function __construct(public readonly string $label, public readonly ?string $url = null)
    {
    }
}

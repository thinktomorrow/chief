<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Models;

/**
 * Class ModelDetails
 *
 * @property $key
 * @property $singular
 * @property $plural
 * @property $internal_label
 * @property $title
 * @property $subtitle
 * @property $intro
 */
class ManagerModelDetails extends ModelDetails
{
    public function __construct(string $key, string $singular, string $plural, string $internal_label, string $title, string $subtitle, string $intro)
    {
        // Default model details
        $this->values['key'] = $key;
        $this->values['singular'] = $singular;
        $this->values['plural'] = $plural;
        $this->values['internal_label'] = $internal_label;

        // Manager model details
        $this->values['title'] = $title;
        $this->values['subtitle'] = $subtitle;
        $this->values['intro'] = $intro;
    }
}

<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\TranslatableFields\Field;
use Thinktomorrow\Chief\Common\TranslatableFields\FieldType;
use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;

class Setting extends Model
{
    public $table      = 'settings';
    public $timestamps = false;
    public $guarded    = [];
    public $casts      = [
        'field' => 'array',
    ];

    public function hasField(): bool
    {
        if( ! $this->field || !is_array($this->field)) return false;

        if( ! isset($this->field['type'])) return false;

        return true;
    }

    public function getField()
    {
        if( ! $this->hasField()) return null;

        $field = FieldType::fromString($this->field['type']);

        if( isset($this->field['label']) ) $field->label($this->field['label']);
        if( isset($this->field['description']) ) $field->description($this->field['description']);

        return $field;
    }
}
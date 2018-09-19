<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\TranslatableFields\Field;
use Thinktomorrow\Chief\Common\TranslatableFields\FieldType;
use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;
use Thinktomorrow\Chief\Common\TranslatableFields\InputField;

class Setting extends Model
{
    public $table = 'settings';
    public $timestamps = false;
    public $guarded = [];

    private static $fieldsFromConfig;

    public function getFieldAttribute()
    {
        $fields = $this->fieldsFromConfig();

        if (!isset($fields[$this->key])) {
            return $this->defaultField();
        }

        return is_callable($fields[$this->key])
            ? call_user_func($fields[$this->key])
            : $fields[$this->key];
    }

    private static function fieldsFromConfig()
    {
        if (static::$fieldsFromConfig) {
            return static::$fieldsFromConfig;
        }

        return static::$fieldsFromConfig = config('thinktomorrow.chief.settingFields', []);
    }

    private function defaultField()
    {
        return InputField::make()
                    ->label(ucfirst(str_replace(['-','_','.'], ' ', $this->key)));
    }

    public static function refreshFieldsFromConfig()
    {
        static::$fieldsFromConfig = null;
    }
}

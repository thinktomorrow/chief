<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\Fields\InputField;

class Setting extends Model
{
    public $table = 'settings';
    public $timestamps = false;
    public $guarded = [];

    private static $fieldsFromConfig;

    public function getFieldAttribute()
    {
        $fields = $this->fieldsFromConfig();

        // TODO: a fieldgroup should be used here to filter / find by key
        foreach($fields as $field){

            if(is_callable($field)){
                $field = call_user_func($field);
            }

            if($field->key != $this->key) continue;

            return $field;
        }

        return $this->defaultField();
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
        return InputField::make($this->key)
                    ->label(ucfirst(str_replace(['-','_','.'], ' ', $this->key)));
    }

    public static function refreshFieldsFromConfig()
    {
        static::$fieldsFromConfig = null;
    }
}

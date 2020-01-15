<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fields\Types\InputField;

class Setting extends Model
{
    const HOMEPAGE = 'homepage';

    public $table = 'settings';
    public $timestamps = false;
    public $guarded = [];
    public $casts = [
        'value' => 'json',
    ];

    private static $fieldsFromConfig;

    public static function findByKey(string $key)
    {
        return static::where('key', $key)->first();
    }

    public function getFieldAttribute()
    {
        $fields = $this->fieldsFromConfig();

        // TODO: a fieldgroup should be used here to filter / find by key
        foreach ($fields as $field) {
            if (is_callable($field)) {
                $field = call_user_func($field);
            }

            if ($field->key != $this->key) {
                continue;
            }

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

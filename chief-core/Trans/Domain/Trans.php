<?php

namespace Chief\Trans\Domain;

use Chief\Locale\Translatable;
use Chief\Locale\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;use Illuminate\Support\Str;

class Trans extends Model implements TranslatableContract
{
    use Translatable, BaseTranslatable;
    public $translatedAttributes = ['value'];
    public $table = 'trans';

    public static function make($key, $group_id, $label = null, $description = null, $type = 'sentence')
    {
        $trans = new self;
        $trans->key = $key;
        $trans->group_id = $group_id;
        $trans->label = $label ?: self::suggestLabel($key);
        $trans->description = $description;
        $trans->type = $type;
        $trans->save();

        return $trans;
    }

    /**
     * Cleanup the translation key for safe key construction.
     *
     * @param $key
     * @return string
     */
    public static function cleanup($key)
    {
        $parts = collect(explode('.',$key));

        return $parts->map(function($part){
            return strtolower(Str::slug($part));
        })->implode('.');
    }

    private static function suggestLabel($key)
    {
        // Remove first part since that part equals the group slug
        $key = substr($key,strpos($key,'.')+1);

        $label = str_replace('.',' ',$key);
        return ucfirst($label);
    }

    public static function suggestType($value)
    {
        if(strip_tags($value) != $value) return 'paragraph';

        if(strlen($value) < 50) return 'word';

        return 'sentence';
    }

    public function group()
    {
        return $this->belongsTo(Transgroup::class);
    }

    public static function findByKey($key)
    {
        return self::where('key',$key)->first();
    }

    /**
     * Get translation for given key and locale from database.
     *
     * @param $key
     * @param array $replace
     * @param null $locale
     * @param bool $fallback
     * @return mixed|null
     */
    public static function translateByKey($key, array $replace= [], $locale = null, $fallback = true)
    {
        if(!$trans = self::findByKey($key)) return null;

        if(!$translation = $trans->getTranslation($locale)) return null;

        $line = $translation->value;

        foreach ($replace as $key => $value) {
            $line = str_replace(':'.$key, $value, $line);
        }

        return $line;
    }

    /**
     * Get all available translation lines.
     * Each line returns the locale, key and value of the translation
     *
     * @param null $locale
     * @param null $group_id
     * @return \Illuminate\Support\Collection
     */
    public static function getTranslationLines($locale = null,$group_id = null, $flattened = false)
    {
        $builder = self::join('trans_translations','trans.id','=','trans_translations.trans_id')
            ->select(['trans.*','trans_translations.locale','trans_translations.value']);

        // Since the dimsav translatable model trait injects its behaviour and overwrites our results
        // with the current locale, we will need to fetch results straight from the db instead.
        if($flattened)
        {
            $builder = DB::table('trans')->join('trans_translations','trans.id','=','trans_translations.trans_id')
                ->select(['trans.*','trans_translations.locale','trans_translations.value']);
        }

        if($locale) $builder = $builder->where('trans_translations.locale',$locale);
        if($group_id) $builder = $builder->where('group_id',$group_id);

        $translations = collect($builder->get());

        if($flattened)
        {
            return $translations->groupBy('locale')->map(function ($locale_collection) {
                return $locale_collection->lists('value', 'key')->toArray();
            });
        }

        return $translations;
    }

    /**
     * @param null $locale
     * @param null $group_id
     * @return \Illuminate\Support\Collection
     */
    public static function getFlattenedTranslationLines($locale = null, $group_id = null)
    {
        return self::getTranslationLines($locale,$group_id,true);
    }

    /**
     * @param null $locale
     * @param null $group_id
     * @return static
     */
    public static function getByGroup($locale = null, $group_id = null)
    {
        $translations = self::getTranslationLines($locale, $group_id, false);

        return $translations->unique('id');
    }



    public function isWord()
    {
        return $this->type == 'word';
    }

    public function isSentence()
    {
        return $this->type == 'sentence';
    }

    public function isParagraph()
    {
        return $this->type == 'paragraph';
    }

//    /**
//     * Convert collection to raw array where
//     * autotranslations will not be triggered
//     *
//     * @param Collection $collection
//     * @return array
//     */
//    private static function convertToRawArray(Collection $collection)
//    {
//        return array_map(function ($value) {
//            return $value instanceof Arrayable ? $value->toRawArray() : $value;
//        }, $collection->all());
//    }

}
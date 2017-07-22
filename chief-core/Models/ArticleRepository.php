<?php

namespace Chief\Models;

use Chief\Locale\TranslatableContract;
use Chief\Locale\TranslatableController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleRepository extends Model
{

    use TranslatableController;


    private $request;

    public function create(Request $request)
    {
//        $this->validateRequest();

        $this->request = $request;
        $article    = new Article;
        $article->save();

        $this->saveArticleTranslations($article);
//        $this->uploadArticleImages($article);

        return $article;
    }

    private function saveArticleTranslations(Article $article)
    {
        $translations = collect($this->request->get('trans'))->map(function($trans,$locale) use($article){
            $trans['title']             = strip_tags($trans['title']);
            $trans['slug']              = Str::slug(strip_tags($trans['title']));
            $trans['content']           = cleanupHTML($trans['content']);
            $trans['short']             = strip_tags($trans['short']);
            $trans['meta_description']  = strip_tags($trans['meta_description']);


            return $trans;
        });

        $this->saveTranslations($translations, $article, [
            'title','slug','content','short','meta_description'
        ]);
    }

    /**feature
     * Override the default behaviour so we can assert a unique slug
     *
     * @param TranslatableContract $entity
     * @param array $keys
     * @param $translation
     * @param $available_locale
     */
    protected function updateTranslation(TranslatableContract $entity, array $keys, array $translation, $available_locale)
    {
        $attributes = [];

        foreach ($keys as $key)
        {
            if(isset($translation[$key]))
            {
                $attributes[$key] = $translation[$key];

                if('slug' == $key)
                {
                    $attributes[$key] = UniqueSlug::make(new ArticleTranslation)->get($translation[$key],$entity->getTranslation($available_locale));
                }
            }
        }

        $entity->updateTranslation($available_locale, $attributes);
    }

    private function uploadArticleImages(Article $article)
    {
        if(!$this->request->get('image')) return;

        foreach($this->request->get('image') as $locale => $image)
        {
            if ($this->isCompletelyEmpty(['thumb_datauri'], $image) && $locale !== Locale::getDefault() )
            {
                continue;
            }
            $filename = md5(time() . uniqid()) . '.png';
            $asset = (new ArticleImageAsset($article))->datauri($image['thumb_datauri'],$filename);
            $article->saveTranslation($locale,'image',$asset->getFilename());
        }
    }


    private function validateRequest()
    {
        $rules = $attributes = $messages = [];
        foreach ($this->request->get('trans') as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['title','content','short','meta_description'], $trans) && $locale !== Locale::getDefault() )
            {
                continue;
            }
            $rules['trans.' . $locale . '.title']               = 'required|max:200';
            $rules['trans.' . $locale . '.text']                = 'required|max:1500';
            $rules['trans.' . $locale . '.short']               = 'required|max:700';
            $rules['trans.' . $locale . '.meta_description']    = 'required';

            $attributes['trans.' . $locale . '.title']              = strtoupper($locale). ' titel';
            $attributes['trans.' . $locale . '.text']               = strtoupper($locale). ' tekst';
            $attributes['trans.' . $locale . '.short']              = strtoupper($locale). ' korte omschrijving';
            $attributes['trans.' . $locale . '.meta_description']   = strtoupper($locale). ' SEO omschrijving';
        }

        $this->validate($this->request, $rules,$messages,$attributes);
        $this->validateImages();
    }

    private function validateImages()
    {
        if(!$this->request->get('image')) return;

        $rules = $attributes = $messages = [];
        foreach ($this->request->get('image') as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['thumb_datauri'], $trans) && $locale !== Locale::getDefault() )
            {
                continue;
            }
            $rules['image.' . $locale . '.thumb_datauri']       = ArticleImageAsset::$createRules['file'];

            $attributes['image.' . $locale . '.thumb_datauri']  = strtoupper($locale). ' image';
        }

        $messages = ArticleImageAsset::$messages;

        $this->validate($this->request, $rules, $messages, $attributes);
    }
}

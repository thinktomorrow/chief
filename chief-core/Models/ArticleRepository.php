<?php

namespace Chief\Models;

use Carbon\Carbon;
use Chief\Locale\Locale;
use Chief\Locale\TranslatableContract;
use Chief\Locale\TranslatableController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleRepository extends Model
{
    use TranslatableController;

    private $request;

    public function create(Request $request)
    {
        $this->request = $request;
        $this->validateRequest();

        $article                = new Article;
        $article->publication   = Carbon::createFromFormat('d-m-Y', $request->publication);
        $article->save();

        $this->saveArticleTranslations($article);

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
            $rules['trans.' . $locale . '.content']             = 'required|max:1500';
            $rules['trans.' . $locale . '.short']               = 'required|max:700';
            $rules['trans.' . $locale . '.meta_description']    = 'required';

            $attributes['trans.' . $locale . '.title']              = strtoupper($locale). ' titel';
            $attributes['trans.' . $locale . '.content']            = strtoupper($locale). ' tekst';
            $attributes['trans.' . $locale . '.short']              = strtoupper($locale). ' korte omschrijving';
            $attributes['trans.' . $locale . '.meta_description']   = strtoupper($locale). ' SEO omschrijving';
        }

        Validator::make($this->request->all(), $rules, $messages, $attributes)->validate();
    }

    public function edit(Request $request, $id)
    {
        $this->request = $request;
        $this->validateRequest();

        $asset      = null;
        $article    = Article::findOrFail($id);
        ($this->request->has('published')) ? $article->publish() : $article->draft();

        //Loops over the uploaded assets and attaches them to the model
        collect($request->trans)->each(function($translation, $locale) use($article){
            if($trans = $translation['files']){
                collect($trans)->each(function($asset_id, $type)use($article, $locale){
                    if($asset_id){
                        $asset = Asset::find($asset_id);
                        $article->addFile($asset, $type , $locale);
                    }
                });
            }
        });

        $article->save();
        $this->saveArticleTranslations($article);

        return $article;
    }

    public function remove($id)
    {
        $article = Article::findOrFail($id);

        $article->delete();
        $message = 'Het nieuwsartikel werd verwijderd.';

        return $message;
    }
}

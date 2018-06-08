<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Common\UniqueSlug;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Pages\Page;

class PageCreateRequest extends FormRequest
{
    use TranslatableCommand;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('chief')->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $translations = $this->enforceUniqueSlug($this->request->get('trans'));            

        foreach ($translations as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['title', 'content', 'short'], $trans) && $locale !== app()->getLocale())
            {
                continue;
            }

            $rules['trans.' . $locale . '.title']   = 'required|max:200';
            $rules['trans.' . $locale . '.slug']    = 'required|unique:page_translations,slug|max:200';
            $rules['trans.' . $locale . '.short']   = 'max:700';
            $rules['trans.' . $locale . '.content'] = 'required|max:1500';
        }

        return $rules;
    }

    public function attributes()
    {
        foreach ($this->request->get('trans') as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['title', 'content', 'short'], $trans) && $locale !== app()->getLocale())
            {
                continue;
            }

            $attributes['trans.' . $locale . '.title']      = 'Title';
            $attributes['trans.' . $locale . '.slug']       = 'Slug';
            $attributes['trans.' . $locale . '.content']    = 'Text';
            $attributes['trans.' . $locale . '.short']      = 'Short';
        }

        return $attributes;
    }

    /**
     * @param array $translations
     * @param $page
     * @return array
     */
    private function enforceUniqueSlug(array $translations): array
    {
        foreach ($translations as $locale => $translation) {
            $translation['slug']    = UniqueSlug::make(new PageTranslation())->get($translation['title'], (new Page())->getTranslation($locale));
            $translations[$locale]  = $translation;
        }

        return $translations;
    }

}

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
        $translations = $this->request->get('trans');
        foreach ($translations as $locale => $trans) {
            if ($this->isCompletelyEmpty(['title', 'content', 'short'], $trans) && $locale !== app()->getLocale()) {
                unset($translations[$locale]);
                $this->request->set('trans', $translations);
                continue;
            }

            $rules['trans.' . $locale . '.title']   = 'required|max:200';
            $rules['trans.' . $locale . '.short']   = 'max:700';
            $rules['trans.' . $locale . '.content'] = 'required|max:1500';
        }

        return $rules;
    }

    public function attributes()
    {
        foreach ($this->request->get('trans') as $locale => $trans) {
            if ($this->isCompletelyEmpty(['title', 'content', 'short'], $trans) && $locale !== app()->getLocale()) {
                continue;
            }

            $attributes['trans.' . $locale . '.title']      = 'Titel';
            $attributes['trans.' . $locale . '.slug']       = 'Permalink';
            $attributes['trans.' . $locale . '.content']    = 'Inhoud';
            $attributes['trans.' . $locale . '.short']      = 'Korte omschrijving';
        }

        return $attributes;
    }
}

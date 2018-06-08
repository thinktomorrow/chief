<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

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
        foreach ($this->request->get('trans') as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['title', 'content', 'short'], $trans) && $locale !== app()->getLocale())
            {
                continue;
            }

            $rules['trans.' . $locale . '.title']   = 'required|unique:page_translations,title|max:200';
            $rules['trans.' . $locale . '.text']    = 'required|max:1500';
            $rules['trans.' . $locale . '.short']   = 'max:700';
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

            $attributes['trans.' . $locale . '.title']   = 'Title';
            $attributes['trans.' . $locale . '.text']    = 'Content';
            $attributes['trans.' . $locale . '.short']   = 'Short';
        }

        return $attributes;
    }

}

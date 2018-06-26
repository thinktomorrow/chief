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
        $translations = $this->request->get('trans');
        foreach ($translations as $locale => $trans) {
            if ($this->isCompletelyEmpty(['title'], $trans) && $locale !== config('app.locale')) {
                unset($translations[$locale]);
                $this->request->set('trans', $translations);
                continue;
            }

            $rules['trans.' . $locale . '.title']   = 'required|max:200';
        }

        return $rules;
    }

    public function attributes()
    {
        foreach ($this->request->get('trans') as $locale => $trans) {
            $attributes['trans.' . $locale . '.title']      = 'Titel';
        }

        return $attributes;
    }
}

<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class PageUpdateRequest extends FormRequest
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
        $rules = [];

        $translations = $this->request->get('trans', []);
        foreach ($translations as $locale => $trans) {
            if ($this->isCompletelyEmpty(['title', 'slug'], $trans) && $locale !== config('app.locale')) {
                continue;
            }

            $rules['trans.' . $locale . '.title'] = 'required|max:200';
            $rules['trans.' . $locale . '.slug']  = 'unique:page_translations,slug,' . $this->id . ',page_id';
        }

        if ($this->request->get('start_at') != null) {
            $rules['start_at'] = 'before:end_at';
        }

        if ($this->request->get('end_at') != null) {
            $rules['end_at'] = 'after:start_at';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'trans.*.title'     => 'Title',
            'trans.*.slug'      => 'Permalink',
        ];
    }
}

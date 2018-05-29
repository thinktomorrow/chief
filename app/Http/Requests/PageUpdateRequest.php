<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PageUpdateRequest extends FormRequest
{
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
        return [
            'trans.*.title'     => 'required|unique:page_translations,title,'. $this->id . ',page_id|max:200',
            'trans.*.slug'      => 'required|unique:page_translations,slug,' . $this->id . ',page_id|distinct',
            'trans.*.content'   => 'required|max:1500',
            'trans.*.short'     => 'max:700',

        ];
    }

    public function attributes()
    {
        return [
            'trans.*.title'     => 'Title',
            'trans.*.slug'      => 'Permalink',
            'trans.*.content'   => 'Content',
            'trans.*.short'     => 'Short',
        ];
    }
}

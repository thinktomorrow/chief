<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ModuleUpdateRequest extends FormRequest
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
            'slug' => 'required|unique:module,slug,' . $this->id . ',id',
        ];
    }

    public function attributes()
    {
        return [
            'slug'    => 'Interne link',
        ];
    }
}

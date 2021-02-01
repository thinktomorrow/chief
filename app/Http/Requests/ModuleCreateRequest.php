<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableCommand;

class ModuleCreateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'module_key' => 'required',
            'slug'       => 'required|unique:modules,slug',
        ];
    }

    public function attributes()
    {
        return [
            'module_key'    => 'type',
            'slug'          => 'interne titel',
        ];
    }

    public function messages()
    {
        return [
            'required'    => 'Gelieve een :attribute in te geven, aub.',
            'unique'      => 'De naam van een module moet uniek zijn.'
        ];
    }
}

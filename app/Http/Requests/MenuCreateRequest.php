<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Pages\Page;

class MenuCreateRequest extends FormRequest
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
        $translations = $this->request->get('trans', []);

        $rules['type']      = 'in:custom,internal';
        $rules['page_id']   = 'required_if:type,internal';
        $rules['id']        = 'required_with:page_id|exists:pages,id';
        
        foreach ($translations as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['url', 'label'], $trans) && $locale !== app()->getLocale())
            {
                unset($translations[$locale]);
                $this->request->set('trans', $translations);
                continue;
            }

            $rules['trans.' . $locale . '.label']   = 'required';
            $rules['trans.' . $locale . '.url']     = 'required_if:type,custom|url';
        }
        
        return $rules;
    }

    public function attributes()
    {
        return [
           'id' => 'page id'
        ];
    }

    /**
     * Modify the input values
     *
     * @return void
     */
    protected function prepareForValidation() {

        // get the input
        $input = $this->all();

        if($this->get('type') == 'internal' && ($page_id = $this->get('page_id'))){
            $input['id'] = substr($page_id, strrpos($page_id, '@') + 1);
        }

        // replace old input with new input
        $this->replace($input);
    }
}

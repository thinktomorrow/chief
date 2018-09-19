<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Helpers\Root;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class MenuRequest extends FormRequest
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

    protected function validationData()
    {
        $data = parent::validationData();

        $data = $this->sanitizeUrl($data);

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $translations = $this->request->get('trans', []);
        
        $rules['type']            = 'required|in:custom,internal,collection,nolink';
        $rules['page_id']         = 'required_if:type,internal';
        $rules['collection_type'] = 'required_if:type,collection';

        foreach ($translations as $locale => $trans) {
            if ($this->isCompletelyEmpty(['label'], $trans) && $locale !== config('app.locale')) {
                continue;
            }

            $rules['trans.' . $locale . '.label']   = 'required';
            if ($this->request->get('trans.' . $locale . '.url') != null) {
                $rules['trans.' . $locale . '.url']     = 'url';
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->request->get('trans', []) as $locale => $trans) {
            $attributes['trans.' . $locale . '.label']   = $locale . ' label';
            $attributes['trans.' . $locale . '.url']     = $locale . ' link';
        }

        $attributes['page_id'] = 'Interne pagina';


        return $attributes;
    }

    public function messages()
    {
        return [
            'required_if' => 'Gelieve nog een :attribute te kiezen, aub.',
            'url'         => 'Dit is geen geldige url. Kan je dit even nakijken, aub?',
        ];
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function sanitizeUrl($data)
    {
        foreach ($data['trans'] as $locale => $trans) {
            if (empty($trans['url'])) {
                continue;
            }

            $data['trans'][$locale]['url'] = Root::fromString($trans['url'])->get();
            $this->request->set('trans', $data['trans']);
        }

        return $data;
    }
}

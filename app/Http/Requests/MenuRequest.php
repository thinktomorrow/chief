<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableCommand;
use Thinktomorrow\Url\Root;
use Thinktomorrow\Url\Url;

class MenuRequest extends FormRequest
{
    use TranslatableCommand;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('chief')->user();
    }

    public function validationData()
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
        $translations = $this->request->all('trans');

        $rules['type'] = 'required|in:custom,internal,nolink';
        $rules['owner_reference'] = 'required_if:type,internal';

        foreach ($translations as $locale => $trans) {
            if ($this->isCompletelyEmpty(['label'], $trans) && $locale !== config('app.locale')) {
                continue;
            }

            $rules['trans.'.$locale.'.label'] = 'required';
            if ($this->request->get('trans.'.$locale.'.url') != null) {
                $rules['trans.'.$locale.'.url'] = 'url';
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach (array_keys($this->request->all('trans')) as $locale) {
            $attributes['trans.'.$locale.'.label'] = $locale.' label';
            $attributes['trans.'.$locale.'.url'] = $locale.' link';
        }

        $attributes['owner_reference'] = 'Interne pagina';

        return $attributes;
    }

    public function messages()
    {
        return [
            'required_if' => 'Gelieve nog een :attribute te kiezen, aub.',
            'required' => 'Gelieve een :attribute in te geven, aub.',
            'url' => 'Dit is geen geldige url. Kan je dit even nakijken, aub?',
        ];
    }

    /**
     * @return mixed
     */
    protected function sanitizeUrl(array $data)
    {
        foreach ($data['trans'] as $locale => $trans) {
            if (empty($trans['url'])) {
                continue;
            }

            // Check if it is a relative
            if ($this->isRelativeUrl($trans['url'])) {
                $data['trans'][$locale]['url'] = '/'.trim($trans['url'], '/');
            } elseif (Str::startsWith($trans['url'], ['mailto:', 'tel:', 'https://', 'http://'])) {
                $data['trans'][$locale]['url'] = $trans['url'];
            } else {
                $data['trans'][$locale]['url'] = Url::fromString($trans['url'])->secure()->get();
            }

            $this->request->set('trans', $data['trans']);
        }

        return $data;
    }

    private function isRelativeUrl($url): bool
    {
        $nakedUrl = ltrim($url, '/');

        // Check if passed url is not intended as a host instead of a relative path
        $notIntentedAsRoot = (Root::fromString($url)->getScheme() == null && strpos($url, '.') === false);

        return $notIntentedAsRoot && in_array($url, [$nakedUrl, '/'.$nakedUrl]);
    }
}

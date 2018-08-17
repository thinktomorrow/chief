<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Http\UploadedFile;

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

        if (optional($this->request->get('custom_fields'))['start_at'] != null) {
            $rules['custom_fields.start_at'] = 'before:custom_fields.end_at';
        }

        if (optional($this->request->get('custom_fields'))['end_at'] != null) {
            $rules['custom_fields.end_at'] = 'after:custom_fields.start_at';
        }

        $data = $this->instance()->all();
        if(isset($data['files']))
        {
            foreach($data['files'] as $key => $files)
            {
                if(isset($files['new']))
                {
                    foreach($files['new'] as $index => $file)
                    {
                        if($file instanceof UploadedFile) {
                            $rules['files.'.$key.'.new.'.$index] = 'max:10000';
                        }else{
                            $rules['filessize.'.$key.'.new.'.$index] = 'accepted';
                        }
                    }
                }
            }
        }
        return $rules;
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();

        if(isset($data['files']))
        {
            foreach($data['files'] as $key => $files)
            {
                if(isset($files['new']))
                {
                    foreach($files['new'] as $index => $file)
                    {
                        if(!$file instanceof UploadedFile && (json_decode($data['files'][$key]['new'][$index])->input->size / 1024 / 1024) > 10) {
                            $data['filessize'][$key]['new'][$index] = false;
                        }else{
                            $data['filessize'][$key]['new'][$index] = true;
                        }
                    }
                }
            }
        }

        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        //TODO find a clean way to translate these attribute. array wildcards dont work here.
        return [
            'files.*.new.*' => "bestand",
        ];
    }
}

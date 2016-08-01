<?php

namespace Chief\Trans\Controllers;

use App\Http\Controllers\Controller;
use Chief\Locale\TranslatableController;
use Chief\Trans\Domain\Trans;
use Chief\Trans\Domain\Transgroup;
use Chief\Trans\Handlers\ClearTranslationsOnDisk;
use Chief\Trans\Handlers\SaveTranslationsToDisk;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    use TranslatableController;

    public function create($slug)
    {
        $group = Transgroup::findBySlug($slug);

        return view('admin.trans.create',compact('group'));
    }


    public function edit($slug)
    {
        $available_locales = config('translatable.locales');

        $group = Transgroup::findBySlug($slug);

        $lines = Trans::getByGroup(null,$group->id);

        return view('admin.trans.edit', compact('group','lines','available_locales'));
    }

    public function update(Request $request, $group_id)
    {
        $group = Transgroup::find($group_id);

        $this->saveValueTranslations($request->get('trans'));

        // Resave our cached translation
        app(SaveTranslationsToDisk::class)->clear()->handle();

        return redirect()->route('admin.trans.edit',$group->slug)->with('messages.success', $group->label .' translations have been updated');
    }

    private function saveValueTranslations(array $translations)
    {
        collect($translations)->map(function($translation,$locale){
            collect($translation)->map(function($value,$id) use($locale){

                $value = cleanupHTML($value);

                if(is_null($value) || "" === $value)
                {
                    Trans::find($id)->removeTranslation($locale);
                }
                else
                {
                    Trans::find($id)->saveTranslation($locale,'value',$value);
                }

            });
        });
    }
}
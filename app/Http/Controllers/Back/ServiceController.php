<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Chief\Common\Translatable\TranslatableContract;
use Chief\Common\Translatable\TranslatableController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\EventDispatcher\Tests\Service;

class ServiceController extends Controller
{
    use TranslatableController;

    public function index()
    {
        $services = Service::sequence()->get();

        return view('back.services.index',compact('services'));
    }

    public function create()
    {
        $service = new Service;

        return view('back.services.create',compact('service'));
    }

    public function store(Request $request)
    {
        $this->validateService($request);

        $service = new Service;
        $service->save();

        $this->saveServiceTranslations($service,$request->get('trans'));

        return redirect()->route('back.services.index')->with('messages.success', $service->title .' has been created');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);

        // Make all translations available for our form
        $trans = [];
        foreach ($service->getUsedLocales() as $locale) {
            $trans[$locale] = $service->getTranslation($locale)->toArray();
        }
        $service->trans = $trans;

        return view('back.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $this->validateService($request);

        $service = Service::findOrFail($id);

        $this->saveServiceTranslations($service,$request->get('trans'));

        Service::reorderAgainstSiblings($request->get('sequence'));

        return redirect()->route('back.services.index')->with('messages.success', $service->title .' has been updated');
    }

    private function saveServiceTranslations(Service $service, array $translations)
    {
        // Add unique slugs for each translation
        $translations = collect($translations)->map(function($trans,$locale) use($service){
            $trans['title'] = strip_tags($trans['title']);
            $trans['content'] = cleanupHTML($trans['content']);
            $trans['meta_description'] = strip_tags($trans['meta_description']);
            $trans['slug'] = Str::slug(strip_tags($trans['title']));

            return $trans;
        });

        $this->saveTranslations($translations, $service, [
            'title','content', 'meta_description','slug'
        ]);
    }

    /**
     * Override the default behaviour so we can assert an unique slug
     *
     * @param TranslatableContract $entity
     * @param array $keys
     * @param $translation
     * @param $available_locale
     */
    protected function updateTranslation(TranslatableContract $entity, array $keys, array $translation, $available_locale)
    {
        $attributes = [];

        foreach ($keys as $key)
        {
            if(isset($translation[$key]))
            {
                $attributes[$key] = $translation[$key];

                if('slug' == $key)
                {
                    $attributes[$key] = Service::getUniqueSlug($translation[$key],$entity->id,$available_locale);
                }
            }
        }

        $entity->updateTranslation($available_locale, $attributes);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        $service->delete();
        $message = 'Page has been deleted.';

        return redirect()->route('back.services.index')->with('messages.warning', $message);
    }

    /**
     * @param Request $request
     */
    private function validateService(Request $request)
    {
        $rules = $attributes = [];
        foreach ($request->get('trans') as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['title', 'content'], $trans))
            {
                continue;
            }
            $rules['trans.' . $locale . '.title'] = 'required|max:200';
            $rules['trans.' . $locale . '.content'] = 'required';

            $attributes['trans.' . $locale . '.title'] = strtoupper($locale). ' title';
            $attributes['trans.' . $locale . '.content'] = strtoupper($locale). ' description';
        }

        $this->validate($request, $rules,[],$attributes);
    }
}
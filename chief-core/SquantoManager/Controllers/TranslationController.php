<?php

namespace Chief\SquantoManager\Controllers;

use App\Http\Controllers\Controller;
use Chief\Locale\TranslatableController;
use Chief\Trans\Domain\Trans;
use Chief\Trans\Domain\Transgroup;
use Chief\Trans\Handlers\ClearTranslationsOnDisk;
use Chief\Trans\Handlers\SaveTranslationsToDisk;
use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Page;

class TranslationController extends Controller
{
    use TranslatableController;

    public function index()
    {
        $pages = Page::sequence()->get();

        return view('squanto::index',compact('pages'));
    }

    public function edit($id)
    {
        $available_locales = config('squanto.locales');

        $page = Page::find($id);

        $groupedLines = $this->groupLinesByKey($page);

        return view('squanto::edit', compact('page','available_locales','groupedLines'));
    }

    public function update(Request $request, $page_id)
    {
        $page = Page::find($page_id);

        $this->saveValueTranslations($request->get('trans'));

        // TODO: Resave our cached translation

        return redirect()->route('back.squanto.edit',$page->id)->with('messages.success', $page->label .' translations have been updated');
    }

    private function saveValueTranslations(array $translations)
    {
        collect($translations)->map(function($translation,$locale){
            collect($translation)->map(function($value,$id) use($locale){

                $value = cleanupHTML($value);
                $line = Line::find($id);

                // If line value is not meant to contain tags, we should strip them
                if(!$line->editInEditor()) $value = cleanupString($value);

                if(is_null($value) || "" === $value)
                {
                    $line->removeValue($locale);
                }
                else
                {
                    $line->saveValue($locale,$value);
                }

            });
        });
    }

    /**
     * @param $page
     * @return \Illuminate\Support\Collection
     */
    private function groupLinesByKey($page)
    {
        $groupedLines = collect(['general' => []]);
        $groups = [];

        foreach ($page->lines as $line)
        {
            $keysegment = $this->getFirstSegmentOfKey($line);

            if (!isset($groups[$keysegment]))
            {
                $groups[$keysegment] = [];
            }
            $groups[$keysegment][] = $line;
        }

        // If firstkey occurs more than once, we will group it
        foreach ($groups as $group => $lines)
        {
            if (count($lines) < 2)
            {
                $groupedLines['general'] = array_merge($groupedLines['general'], $lines);
            } else
            {
                $groupedLines[$group] = $lines;
            }
        }

        return $groupedLines;
    }

    /**
     * Get suggestion for a label based on the key
     * e.g. foo.bar.title return bar
     * @return string
     */
    private function getFirstSegmentOfKey(Line $line)
    {
        // Remove first part since that part equals the page
        $key = substr($line->key, strpos($line->key, '.')+1);

        $length = strpos($key, '.')?: strlen($key);
        $key = substr($key,0,$length);

        return $key;
    }
}
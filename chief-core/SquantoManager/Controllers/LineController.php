<?php

namespace Chief\SquantoManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\LineKey;
use Thinktomorrow\Squanto\Domain\Page;
use Thinktomorrow\Squanto\Exceptions\InvalidLineKeyException;

class LineController extends Controller
{
    /**
     * Create new line
     *
     * @param null $page_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($page_id = null)
    {
        // If pageid is passed, the first key (pagekey) is prefilled
        $page = $page_id ? Page::find($page_id) : null;
        $line = new Line();
        $available_locales = config('squanto.locales');

        return view('squanto::create',compact('page','line','available_locales'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'key' => 'required|min:3|max:100|unique:squanto_lines,key',
        ]);

        try{
            $linekey = new LineKey($request->get('key'));

            $page_is_created = !(Page::findByKey($linekey->getPageKey()));

            $line = Line::make($linekey->get());
            $this->saveValueTranslations($line,$request->get('trans'));

            $line->saveSuggestedType();

            $message = $line->key. ' translation line created!' . (($page_is_created) ? ' Since the '.$linekey->getPageKey(). ' page didn\'t exist, it was added as well' : null);

            return redirect()->route('back.squanto.edit',$line->page_id)->with('messages.success',$message);

        }catch(InvalidLineKeyException $e)
        {
            return redirect()->back()->withInput()->withErrors('Invalid format for key. Must contain at least one dot as divider of the page identifier and the key itself: e.g. foo.bar');
        }

        return redirect()->back()->withInput()->withErrors('The line could not be created due to an unknown error.');

    }

    public function edit($id)
    {
        $available_locales = config('squanto.locales');
        $line = Line::find($id);

        return view('squanto::lines.edit', compact('line','available_locales'));
    }

    public function update(Request $request, $id)
    {
        $line = Line::find($id);

        $this->validate($request,[
            'key' => 'required|min:3|max:100|unique:squanto_lines,key,'.$line->id,
        ]);

        try{

            $line->key = $request->get('key'); // Note that the page connection will not get updated!
            $line->type = $request->get('type');
            $line->label = $request->get('label');
            $line->description = $request->get('description');
            $line->save();
            $this->saveValueTranslations($line,$request->get('trans'));

            $linekey = new LineKey($line->key);
            $page_is_created = !(Page::findByKey($linekey->getPageKey()));

            // TODO: alert developer that keys will need be be changed as well / or autosuggest this with easy CONFIRM TO CHANGE IN FOLLOWING FILES:

            $message = $line->key. ' translation line updated!' . (($page_is_created) ? ' Since the '.$linekey->getPageKey(). ' page didn\'t exist, it was added as well' : null);

            return redirect()->route('back.squanto.lines.edit',$line->id)->with('messages.success',$message);

        }catch(InvalidLineKeyException $e)
        {
            return redirect()->back()->withInput()->withErrors('Invalid format for key. Must contain at least one dot as divider of the page identifier and the key itself: e.g. foo.bar');
        }

        return redirect()->back()->withInput()->withErrors('The line could not be updated due to an unknown error.');
    }

    public function destroy($id)
    {
        $line = Line::findOrFail($id);
        $key = $line->key;
        $page = Page::find($line->page_id);

        $line->delete();

        return redirect()->route('back.squanto.edit',$page->id)->with('messages.warning','Line '.$key.' is verwijderd');
    }

    private function saveValueTranslations(Line $line, array $translations)
    {
        collect($translations)->map(function($value,$locale) use($line){

            $value = cleanupHTML($value);

            if(is_null($value) || "" === $value)
            {
                $line->removeValue($locale);
            }
            else
            {
                $line->saveValue($locale,$value);
            }
        });
    }
}
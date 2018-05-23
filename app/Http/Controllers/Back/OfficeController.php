<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::sequence()->get();

        return view('back.offices.index',compact('offices'));
    }

    public function create()
    {
        $office = new Office;

        return view('back.offices.create',compact('office'));
    }

    public function store(Request $request)
    {
        $this->validateOffice($request);

        $office = new Office;
        $office->slug = Office::getUniqueSlug($office->title);
        $office->save();

        $this->saveOffice($office,$request);

        return redirect()->route('back.offices.index')->with('messages.success', 'Office of '.$office->title .' has been added.');
    }

    public function edit($id)
    {
        $office = Office::findOrFail($id);

        return view('back.offices.edit', compact('office'));
    }

    public function update(Request $request, $id)
    {
        $this->validateOffice($request);

        $office = Office::findOrFail($id);

        $this->saveOffice($office,$request);
        Office::reorderAgainstSiblings($request->get('sequence'));
        $this->saveOfficeImage($office,$request);

        return redirect()->route('back.offices.index')->with('messages.success', 'Office of '.$office->title .' has been updated');
    }

    private function saveOffice(Office $office, Request $request)
    {
        $office->title = cleanupString($request->get('title'));
        $office->content = cleanupHTML($request->get('content'));
        $office->country_key = $request->get('country_key');
        $office->slug = Office::getUniqueSlug($office->title,$office->id);

        $office->save();
    }

    private function saveOfficeImage(Office $office,Request $request)
    {
        if(!$request->file('featured_image')) return;

        $filename = (new OfficeImageAsset($office))->upload($request->file('featured_image'))->resizeToDefaults()->getFilename();

        $office->image = $filename;
        $office->save();
    }

    public function destroy($id)
    {
        $office = Office::findOrFail($id);

        $office->delete();
        $message = 'Office of '.$office->title .' has been deleted.';

        return redirect()->route('back.offices.index')->with('messages.warning', $message);
    }

    /**
     * @param Request $request
     */
    private function validateOffice(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:200',
            'content' => 'required',
            'country_key' => 'required',
            'featured_image' => 'mimes:jpeg,jpg,gif,png',
        ]);
    }
}
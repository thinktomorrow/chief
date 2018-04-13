<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Chief\Locale\Locale;
use Chief\Locale\TranslatableController;
use Chief\Models\Notes\FormValues;
use Chief\Models\Notes\Note;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    use TranslatableController;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $notes = Note::all();

        $labels = collect([]);
        $active = $notes->reject(function ($note) use($labels){
            return Carbon::now()->between($note->start_at, $note->end_at);
        })->map(function($note)  use($labels){
            $labels->push($note->content);
            return [$note->start_at, $note->end_at, 'color' => "#b4b4b4", 'link' => route('back.notes.edit', $note->id)];
        })->toArray();
        $dates = array_merge($active, $notes->reject(function ($note)  use($labels){
            return !Carbon::now()->between($note->start_at, $note->end_at);
        })->map(function($note)  use($labels){
            $labels->push($note->content);
            return [$note->start_at, $note->end_at, 'color' => "#6eaf4e", 'link' => route('back.notes.edit', $note->id)];
        })->toArray());


//        $dates = $general->map(function ($note) use($labels){
//            $labels->push($note->content);
//            return [$note->start_at, $note->end_at, 'color' => "#6eaf4e", 'link' => route('back.admin.notes.edit', $note->id)];
//        });
//        $dates = array_merge($dates->toArray(), $payment->map(function ($note) use($labels){
//            $labels->push($note->content);
//            return [$note->start_at, $note->end_at, 'color' => "#ffa500", 'link' => route('back.admin.notes.edit', $note->id)];
//        })->toArray());
//
//        $dates  = $notes->map(function($note){
//            return [$note->start_at, $note->end_at, 'color' => "#6eaf4e", 'link' => route('back.admin.notes.edit', $note->id)];
//        });

        $chart = Charts::create('bar', 'highcharts')
            ->view('back.charts.highcharts.rangebar')
            ->title("Note overview")
            ->elementLabel("")
            ->dimensions(0, 400) // Width x Height
            ->values($dates)
            ->legend(false)
            ->labels($labels);
        $data = compact("chart", "notes");

        return view('back.note.index', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $note = new Note;

        // Defaults
        $note->level = 'info';
        $note->start_at = Carbon::now()->startOfDay();
        $note->end_at = Carbon::now()->addDay(1)->startOfDay();

        return view('back.note.create', [
            'note' => $note,
            'formValues' => new FormValues($note)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validateNote($request);

        $note = Note::add($request->type,
                        $request->level,
                        Carbon::createFromFormat('Y-m-d', $request->start_at),
                        Carbon::createFromFormat('Y-m-d', $request->end_at));

        $this->saveNoteTranslations($note, $request->get('trans'));

        // If we currently are in the period, we publish the note immediately
        if(Carbon::now()->between($note->start_at,$note->end_at))
        {
            $note->publish();
        }

        return redirect()->route('back.notes.index');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $note = Note::find($id);

        // Make all translations available for our form
        $trans = [];
        foreach ($note->getUsedLocales() as $locale) {
            $trans[$locale] = (object)$note->getTranslation($locale)->toArray();
        }
        $note->trans = (object)$trans;

        return view('back.note.edit', [
            'note' => $note,
            'formValues' => new FormValues($note)
        ]);
    }

    /**
     * @param $id
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validateNote($request);

        $note = Note::findOrFail($id);

        $note->type = $request->type;
        $note->level = $request->level;
        $note->start_at = $request->start_at .' 00:00:00';
        $note->end_at = $request->end_at  .' 23:59:59';
        $note->updated_at = Carbon::now(); // Update updated_at timestamp if only translations are changed
        $note->save();

        $this->saveNoteTranslations($note,$request->get('trans'));

        return redirect()->route('back.back.notes.index')->with('messages.success','De note is aangepast.');
    }

    public function publish(Request $request)
    {
        $note = Note::findOrFail($request->get('id'));
        $published = ("true" === $request->checkboxStatus); // string comp. since bool is passed as string

        ($published) ? $note->publish() : $note->draft();

        return response()->json([
            'message'   => $published ? 'note put online' : 'note taken offline',
            'published' => $published,
            'id'        => $note->id
        ],200);
    }

    private function saveNoteTranslations(Note $note, array $translations)
    {
        // Add unique slugs for each translation
        $translations = collect($translations)->map(function($trans,$locale) use($note){
            $trans['content'] = cleanupString($trans['content']);

            return $trans;
        });

        $this->saveTranslations($translations, $note, [
            'content'
        ]);
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $type = $note->type;

        $note->delete();

        return redirect()->route('back.back.notes.index')->with('messages.warning','Note ['.$type.'] is verwijderd.');
    }

    /**
     * @param Request $request
     */
    private function validateNote(Request $request)
    {
        $rules = $attributes = $messages = [];

        $rules['type']      = 'required|in:'. implode(',',Note::$types);
        $rules['level']     = 'required|in:'. implode(',',Note::$levels);
        $rules['start_at']  = 'required|date_format:Y-m-d';
        $rules['end_at']    = 'required|date_format:Y-m-d';

        foreach ($request->get('trans') as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['content'], $trans) && $locale !== Locale::getDefault() )
            {
                continue;
            }
            $rules['trans.' . $locale . '.content'] = 'required';
            $attributes['trans.' . $locale . '.content'] = strtoupper($locale). ' content';
        }

        $this->validate($request, $rules, $messages, $attributes);
    }
}
<?php

namespace Chief\Models\Notes;

use Carbon\Carbon;
use Chief\Common\Traits\Publishable;
use Illuminate\Database\Eloquent\Model;
use Chief\Common\Translatable\Translatable;
use Chief\Common\Translatable\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Optiphar\Site\Notes\NoteReminder;

class Note extends Model implements TranslatableContract
{
    use BaseTranslatable, Translatable, Chief\Traits\Publishable;

    public $table = 'notes';
    public $translatedAttributes = ['content'];
    public $translationForeignKey = 'note_id';
    protected $dates = [ 'start_at', 'end_at' ];

    public static $types = ['general', 'payment', 'delivery'];
    public static $levels = ['info', 'warning', 'error'];

    public static $typeMapping = [
        'general' => 'Algemeen',
        'payment' => 'Betaling',
        'delivery' => 'Levering'
    ];

    public static $levelMapping = [
        'info' => 'Info',
        'warning' => 'Waarschuwing',
        'error' => 'Foutboodschap'
    ];

    public static function render($type)
    {
        if($type != 'general')
        {
            if($note = self::getActiveNote($type))
            {
                return '<div class="note note-' . $note->level . '"><span><i class="fa fa-fw fa-' . $note->level . '"></i>'.
                    $note->content
                    .'</span></div>';
            }
        }else{
            if($note = self::getActiveNote($type)) {
                if(NoteReminder::hasWatched($note, app()->getLocale()))
                {
                    return null;
                }else{
                    NoteReminder::watch($note->id, $note->updated_at, app()->getLocale());

                    return '<div id="note" class="note-' . $note->level . '">' . $note->content . '<a class="close-note" aria-label="Close"><i class="fa fa-inverse fa-times-circle"></i></a></div>';
                }
            }
        }

        return null;
    }

    public function renderTypeBadge()
    {
        $type = self::$typeMapping[$this->type];
        $class = $this->level == 'error' ? 'danger' : $this->level;

        return '<span class="label label-xs label-'.$class.'">'.$type.'</span>';
    }

    public function renderLevelBadge()
    {
        $level = self::$levelMapping[$this->level];
        $class = $this->level == 'error' ? 'danger' : $this->level;

        return '<span class="label label-xs label-'.$class.'">'.$level.'</span>';
    }

    public static function add($type, $level, Carbon $start_at, Carbon $end_at, $content = '', $cookies = false)
    {
        $note           = new self;
        $note->type     = $type;
        $note->level    = $level;
        $note->start_at = $start_at;
        $note->end_at   = $end_at;
        $note->cookies  = $cookies;
        $note->save();

        $note->saveTranslation('nl','content',  $content);
        return $note;
    }

    private static function findByType($type)
    {
        $note = self::where('type', $type)->published()->get();

        return $note;
    }

    private static function getActiveNote($type)
    {
        $notes = self::findByType($type);

        $notes = $notes->reject(function ($note){
            return !Carbon::now()->between($note->start_at, $note->end_at);
        });

        if($notes->count() > 1)
        {
            $note = $notes->sortBy(function($note){
                return implode(' ', [$note->start_at, $note->created_at]);
            })->last();
        }else{
            $note = $notes->first();
        }

        return $note ?: null;
    }
}
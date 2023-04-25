<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DayModel extends Model
{
    protected $guarded = [];
    public $table = 'timetable_days';
    public $timestamps = false;
    public $casts = [
        'slots' => 'array',
        'data' => 'array',
    ];

    public function getContent(?string $locale): ?string
    {
        return $this->getData('content', $locale);
    }

    private function getData(string $key, string $index = null, $default = null)
    {
        $key = $index ? $key .'.'.$index : $key;

        return data_get($this->data, $key, $default);
    }

//    public function fields($model): iterable
//    {
//        yield MultiSelect::make('timetable_id')
//            ->label('Tijdschema')
//            ->options(fn () => app(TimeTableReadRepository::class)->getAllTimeTablesForSelect());
//
//        yield MultiSelect::make('weekday')
//            ->label('Dag van de week')
//            ->options([
//                'maandag',
//                'dindag',
//                'woensdag',
//                'donderdag',
//                'vrijdag',
//                'zaterdag',
//                'zondag',
//            ]);
//
//        yield Repeat::make('hours')->items([
//            Grid::make()->columns(2)->items([
//                Text::make('from'),
//                Text::make('until'),
//            ]),
//        ]);
//
//        yield Text::make('content')
//            ->label('Eigen tekst')
//            ->locales()
//            ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"');
//    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(app(TimeTableModel::class), 'timetable_id');
    }
}

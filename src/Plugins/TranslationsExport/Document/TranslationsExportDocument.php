<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TranslationsExportDocument implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithColumnFormatting
{
    use Exportable;

    private Collection $models;
    private string $locale;
    private array $targetLocales;

    public function __construct(Collection $models, string $locale, array $targetLocales)
    {
        $this->models = $models;
        $this->locale = $locale;
        $this->targetLocales = $targetLocales;
    }

    public function collection()
    {
        return $this->models;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function map($row): array
    {
        return app(ComposeLines::class)->compose($row, $this->locale, $this->targetLocales);
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'original text ('.$this->locale.')',
            ...$this->targetLocales,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
        ];
    }
}

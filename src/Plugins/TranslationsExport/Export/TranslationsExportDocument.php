<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Thinktomorrow\Chief\Resource\Resource;

class TranslationsExportDocument implements FromCollection, WithMapping, WithDefaultStyles, WithStyles, WithHeadings, WithColumnWidths, WithColumnFormatting
{
    use Exportable;

    private Resource $resource;
    private Collection $models;
    private string $locale;
    private array $targetLocales;
    private Collection $styleCollection;

    public function __construct(Resource $resource, Collection $models, string $locale, array $targetLocales)
    {
        $this->resource = $resource;
        $this->models = $models;
        $this->locale = $locale;
        $this->targetLocales = $targetLocales;

        $this->styleCollection = collect();
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

    public function map($row): array
    {
        $composeLines = app(ComposeExportLines::class)
            ->ignoreNonTranslatable()
            ->ignoreEmptyValues()
            ->ignoreOfflineFragments()
            ->ignoreFieldKeys(['url'])
            ->compose($this->resource, $row, $this->locale, $this->targetLocales);

        $this->styleCollection = $this->styleCollection->merge($composeLines->getStyles());

        return $composeLines->getLines()->toArray();
    }

    public function headings(): array
    {
        return [
            'Page',
            'ID',
            'Fragment',
            'Element',
            $this->locale,
            ...$this->targetLocales,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 2,
            'B' => 2,
            'C' => 15,
            'D' => 15,
            'E' => 50,
            'F' => 50,
            'G' => 50,
            'H' => 50,
            'I' => 50,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Configure the default styles
        $defaultStyle->getBorders()->getTop()
            ->setBorderStyle('thin')
            ->setColor(new Color('FF666666'));

        $defaultStyle->getBorders()->getBottom()
            ->setBorderStyle('thin')
            ->setColor(new Color('FF666666'));

        $defaultStyle->getBorders()->getLeft()
            ->setBorderStyle('thin')
            ->setColor(new Color('FF666666'));

        $defaultStyle->getBorders()->getRight()
            ->setBorderStyle('thin')
            ->setColor(new Color('FF666666'));

        $defaultStyle->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);

        return $defaultStyle;
    }

    /**
     * Style options: fill, font, borders, alignment, numberFormat, protection
     *
     * @param Worksheet $sheet
     * @return \array[][]
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A' => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9' ]],
            ],
            'B' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9' ]],
            ],
            'C' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9' ]],
            ],
            'D' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9' ]],
            ],

            // Style the first row as bold text.
            1 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE ]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF333333' ]]],
        ];

    }
}

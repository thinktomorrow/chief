<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Thinktomorrow\Chief\Plugins\Export\Export\Lines\ComposeFieldLines;
use Thinktomorrow\Chief\Resource\Resource;

class ExportResourceDocument implements FromCollection, WithColumnFormatting, WithColumnWidths, WithDefaultStyles, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private Resource $resource;

    private Collection $models;

    private array $locales;

    private bool $ignoreNonLocalized;

    /** Keep track of the already exported shared fragments, this way we only need to export them once, the first time they appear */
    private array $ignoredSharedFragments = [];

    public function __construct(Resource $resource, Collection $models, array $locales, bool $ignoreNonLocalized = true)
    {
        $this->resource = $resource;
        $this->models = $models;
        $this->locales = $locales;
        $this->ignoreNonLocalized = $ignoreNonLocalized;
    }

    public function collection()
    {
        return $this->models;
    }

    public function columnFormats(): array
    {
        return [
            ...array_fill_keys(range('A', 'Z'), NumberFormat::FORMAT_TEXT),
        ];
    }

    public function map($row): array
    {
        $composeLines = app(ComposeFieldLines::class)
            ->ignoreFragments($this->ignoredSharedFragments)
            ->ignoreNonLocalized($this->ignoreNonLocalized)
            ->ignoreEmptyValues()
            ->ignoreFieldKeys(['url'])
            ->ignoreOfflineFragments()
            ->compose($this->resource, $row, $this->locales);

        // Add the new shared fragments to our ignored list
        $this->ignoredSharedFragments = array_unique(array_merge($this->ignoredSharedFragments, $composeLines->getIgnoredSharedFragments()));

        return $composeLines->getLines()->toArray();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Pagina',
            'Fragment',
            'Element',
            ...($this->ignoreNonLocalized ? [] : ['Tekst']),
            ...$this->locales,
            'Opmerking',
        ];
    }

    public function columnWidths(): array
    {
        $keys = array_slice(range($this->ignoreNonLocalized ? 'E' : 'F', 'Z'), 0, count($this->locales));
        $columns = array_combine($keys, array_fill(0, count($this->locales), 50));

        return [
            'A' => 3,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            ...$this->ignoreNonLocalized ? [] : ['E' => 50],
            ...$columns,
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
     * @return \array[][]
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
            ],
            'B' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
            ],
            'C' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
            ],
            'D' => [
                'alignment' => ['wrapText' => false],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
            ],

            // Style the first row as bold text.
            1 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF333333']]],
        ];

    }
}

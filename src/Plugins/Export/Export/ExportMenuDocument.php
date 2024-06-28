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
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportMenuDocument implements FromCollection, WithMapping, WithDefaultStyles, WithStyles, WithHeadings, WithColumnWidths, WithColumnFormatting
{
    use Exportable;

    private Collection $models;
    private array $locales;
    private Collection $styleCollection;

    public function __construct(Collection $models, array $locales)
    {
        $this->models = $models;
        $this->locales = $locales;

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
        $values = array_reduce(
            $this->locales,
            fn ($carry, $locale) => [...$carry, $row->getUrl($locale), $row->getLabel($locale), $row->getOwnerLabel($locale)],
            []
        );

        return [
            encrypt($row->id),
            $row->menu_type,
            $row->type,
            ...$values,
        ];
    }

    public function headings(): array
    {
        $columns = array_reduce(
            $this->locales,
            fn ($carry, $locale) => [...$carry, "{$locale} url", "{$locale} label", "{$locale} owner label"],
            []
        );

        return [
            'ID',
            'Menu',
            'Type link',
            ...$columns,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 2,
            'B' => 10,
            'C' => 10,
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 25,
            'L' => 25,
            'M' => 25,
            'N' => 25,
            'O' => 25,
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

            // Style the first row as bold text.
            1 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE ]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF333333' ]]],
        ];

    }
}

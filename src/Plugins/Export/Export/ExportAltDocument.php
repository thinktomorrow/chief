<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Thinktomorrow\AssetLibrary\AssetContract;

class ExportAltDocument implements FromCollection, WithColumnFormatting, WithColumnWidths, WithDefaultStyles, WithDrawings, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private Collection $models;

    private array $locales;

    private Collection $styleCollection;

    // Keep track of pending drawings to be added later
    private array $pendingDrawings;

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

    public function columnFormats(): array
    {
        return [];
    }

    public function map($row): array
    {
        /** @var AssetContract $asset */
        $asset = $row;

        $alts = $asset->getData('alt');

        if (! $alts) {
            $alts = [];
        }

        $values = array_reduce(
            $this->locales,
            fn ($carry, $locale) => [...$carry, $asset->getData('alt.'.$locale)],
            []
        );

        $this->pendingDrawings[] = $asset->getPath('thumb');

        return [
            encrypt($asset->id),
            '',
            $asset->getUrl(),
            $asset->getFileName(),
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
            'Afbeelding',
            'Link',
            'Bestandsnaam',
            ...$columns,
        ];
    }

    public function drawings()
    {
        return collect($this->pendingDrawings)->map(function ($imagePath, $index) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
            $drawing->setPath($imagePath); // Assuming the URL is a valid path to the image
            $drawing->setHeight(40);
            $drawing->setWidth(40);
            $drawing->setCoordinates('B'.$index + 1); // Adjust coordinates as needed

            $drawing->setOffsetX(2);
            $drawing->setOffsetY(2);

            $drawing->setResizeProportional(true);

            return $drawing;
        })->all();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 2,
            'B' => 44,
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

            // Style the first row as bold text.
            1 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF333333']]],
        ];

    }
}

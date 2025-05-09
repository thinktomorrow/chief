<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
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
use Thinktomorrow\Squanto\Database\DatabaseLine;
use Thinktomorrow\Squanto\Domain\LineKey;
use Thinktomorrow\Squanto\Manager\Pages\LineViewModel;

class ExportTextDocument implements FromCollection, WithColumnWidths, WithDefaultStyles, WithHeadings, WithMapping, WithStyles
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
     * @param  DatabaseLine  $row
     */
    public function map($row): array
    {
        $values = array_reduce(
            $this->locales,
            fn ($carry, $locale) => [...$carry, $row->dynamic('value', $locale)],
            []
        );

        $page = LineKey::fromString($row->key)->pageKey();

        $lineViewModel = new LineViewModel($row);
        $label = $lineViewModel->sectionKey().($lineViewModel->sectionKey() !== $lineViewModel->label() ? ' '.$lineViewModel->label() : '');
        $label = str_replace('_', ' ', $label);

        return [
            encrypt($row->key),
            $page,
            $label,
            ...$values,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Groep',
            'Label',
            ...$this->locales,
            'Opmerking',
        ];
    }

    public function columnWidths(): array
    {
        $keys = array_slice(range('D', 'Z'), 0, count($this->locales));
        $columns = array_combine($keys, array_fill(0, count($this->locales), 50));

        return [
            'A' => 3,
            'B' => 20,
            'C' => 30,
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

            // Style the first row as bold text.
            1 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF333333']]],
        ];

    }
}

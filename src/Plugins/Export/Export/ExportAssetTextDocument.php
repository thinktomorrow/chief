<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAi\Prompts\OpenAiImageAltPrompt;

class ExportAssetTextDocument implements FromCollection, WithColumnFormatting, WithColumnWidths, WithDefaultStyles, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private Collection $models;

    private array $locales;

    private Collection $styleCollection;

    // Keep track of pending drawings to be added later
    private array $pendingDrawings;

    private bool $hive;

    public function __construct(Collection $models, array $locales, bool $hive = false)
    {
        $this->models = $models;
        $this->locales = $locales;

        $this->styleCollection = collect();
        $this->hive = $hive;
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

        $values = array_reduce(
            $this->locales,
            fn ($carry, $locale) => [...$carry, $asset->getData('alt.'.$locale)],
            []
        );

        if ($this->hive) {
            try {
                $altTexts = app(OpenAiImageAltPrompt::class)->prompt([
                    'asset_id' => $asset->id,
                    'locales' => $this->locales,
                ])->getAltTexts();

                foreach ($altTexts as $locale => $altText) {
                    $valueIndex = array_search($locale, $this->locales);
                    $values[$valueIndex] = $altText;
                }

            } catch (\Exception $e) {
                // If the OpenAI prompt fails, we can still export the asset without alt texts.
                // Log the error or handle it as needed.
                Log::error('Failed to generate alt text for asset ID '.$asset->id.': '.$e->getMessage());
            }
        }

        return [
            encrypt($asset->id),
            $asset->getUrl(),
            $asset->getBaseName(),
            ...$values,
        ];
    }

    public function headings(): array
    {
        $columns = array_reduce(
            $this->locales,
            fn ($carry, $locale) => [...$carry, "{$locale} alt"],
            []
        );

        return [
            'ID',
            'Afbeelding',
            'Bestandsnaam',
            ...$columns,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 2,
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

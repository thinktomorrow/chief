<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAi\Prompts;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\Presets\HivePromptDefaults;
use Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAiDriver;

class OpenAiImageAltPrompt implements HivePrompt
{
    use HivePromptDefaults;

    private string $label = 'Verzin alt tekst voor deze afbeelding';

    private array $altTexts = [];

    public function getPayload(Field $field, ?string $locale, $livewireComponent): array
    {
        $payload = [];

        if (isset($livewireComponent->previewFile)) {
            $payload['asset_id'] = $livewireComponent->previewFile->id;
        }

        return $payload;
    }

    public function prompt(array $payload): static
    {
        $asset = Asset::find($payload['asset_id']);

        // Get filepath of original image
        $path = $asset->getPath('thumb');

        if (! file_exists($path)) {
            throw new \Exception('File not found for asset '.$asset->id.', path: '.$path);
        }

        $imageData = base64_encode(file_get_contents($path));

        $projectContext = 'Onze Minimax M3 waterontharder haalt de kalk uit je water en zet zo hard water om in zacht water. Minimax is voor velen de ideale waterontharder. Hij is klein, werkt op waterdruk en heeft geen elektriciteit nodig. Enkele kernwoorden van het bedrijf zijn: waterontharder, waterverzachter, waterhardheid, hard water, kalk, zoutblokken, gezond water.';

        $response = app(OpenAiDriver::class)->chat([
            'model' => config('chief-hive.openai.model', 'gpt-4o'),
            'messages' => [
                ['role' => 'system', 'content' => 'Je bent een seo assistent die alt-teksten genereert voor afbeeldingen. Output als JSON object met elke locale als key. Hier is een beetje context over de site waarin de afbeelding gebruikt wordt: '.$projectContext],
                ['role' => 'user', 'content' => [
                    //                    ['type' => 'text', 'text' => 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst. Dit voor de volgende locales: '.implode(',', ChiefSites::locales())],
                    ['type' => 'text', 'text' => 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst. Geef me drie voorstellen die verschillen qua creativiteit.'],
                    ['type' => 'image_url', 'image_url' => [
                        'url' => 'data:image/jpeg;base64,'.$imageData,
                    ]],
                ]],
            ],
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 200,
        ]);

        $content = $response->toArray()['choices'][0]['message']['content'];

        if (! $content) {
            throw new \Exception('No content returned from OpenAI for asset '.$asset->id);
        }

        $this->altTexts = json_decode($content, true);

        return $this;
    }

    public function getAltTexts(): array
    {
        return $this->altTexts;
    }
}

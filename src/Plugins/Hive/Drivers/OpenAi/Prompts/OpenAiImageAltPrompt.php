<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAi\Prompts;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\Presets\HivePromptDefaults;
use Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAiDriver;
use Thinktomorrow\Chief\Sites\ChiefSites;

class OpenAiImageAltPrompt implements HivePrompt
{
    use HivePromptDefaults;

    private string $label = 'Genereer alt teksten voor een afbeelding';

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

        $projectContext = config('chief-hive.context.default', '');
        $systemContent = 'Je bent een seo assistent die alt-teksten genereert voor afbeeldingen. Output als JSON object met elke locale als key. Hier is een beetje context over de site waarin de afbeelding gebruikt wordt: '.$projectContext;
        $userContent = 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst met een maximum van 125 karakters. Dit voor de volgende locales: '.implode(',', ChiefSites::locales());

        $response = app(OpenAiDriver::class)->chat([
            'model' => config('chief-hive.openai.model', 'gpt-4o'),
            'messages' => [
                ['role' => 'system', 'content' => $systemContent],
                ['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => $userContent],
                    //                    ['type' => 'text', 'text' => 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst. Geef me drie voorstellen die verschillen qua creativiteit.'],
                    ['type' => 'image_url', 'image_url' => [
                        'url' => 'data:image/jpeg;base64,'.$imageData,
                    ]],
                ]],
            ],
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 600,
        ]);

        $content = $response->toArray()['choices'][0]['message']['content'];

        if (! $content) {
            throw new \Exception('No content returned from OpenAI for asset '.$asset->id);
        }

        $altTexts = json_decode($content, true);

        if (! $altTexts || ! is_array($altTexts)) {
            throw new \Exception('Invalid JSON returned from OpenAI for asset '.$asset->id.': '.$content);
        }

        $this->altTexts = $altTexts;

        return $this;
    }

    public function getAltTexts(): array
    {
        return $this->altTexts;
    }
}

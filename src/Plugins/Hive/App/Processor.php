<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App;

use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePromptResponse;
use Thinktomorrow\Chief\Plugins\Hive\Drivers\DriverFactory;

class Processor
{
    public function process(HivePrompt $prompt): HivePromptResponse
    {
        $driver = DriverFactory::create($prompt->getDriver());

        return $driver->chat($prompt);

        // Get filepath of original image
        $path = $asset->getPath();

        if (! file_exists($path)) {
            $this->error('File not found for asset '.$asset->id);

            continue;
        }

        $this->info('Generating alt for asset '.$asset->id.', bestandsnaam: '.$asset->getFileName());

        $imageData = base64_encode(file_get_contents($path));

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        /** @var OpenAI\Responses\Chat\CreateResponseMessage $response */
        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'Je bent een seo assistent die alt-teksten genereert voor afbeeldingen.'],
                ['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst, zowel in NL als FR. Output als JSON met de keys "nl" en "fr".'],
                    ['type' => 'image_url', 'image_url' => [
                        'url' => 'data:image/jpeg;base64,'.$imageData,
                    ]],
                ]],
            ],
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 100,
        ]);

        $content = $response->toArray()['choices'][0]['message']['content'];
        $alts = json_decode($content, true);

        app(FileApplication::class)->updateAssetData($asset->id, [
            'alt' => [
                'nl' => $alts['nl'],
                'fr' => $alts['fr'],
            ],
        ]);

    }
}

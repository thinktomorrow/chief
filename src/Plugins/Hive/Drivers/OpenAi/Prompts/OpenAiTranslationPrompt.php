<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAi\Prompts;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\Presets\HivePromptDefaults;
use Thinktomorrow\Chief\Plugins\Hive\Drivers\OpenAiDriver;
use Thinktomorrow\Chief\Sites\ChiefSites;

class OpenAiTranslationPrompt implements HivePrompt
{
    use HivePromptDefaults;

    private string $label = 'Vertaal teksten via OpenAI';

    private array $result = [];

    public function prompt(array $payload): static
    {
        $texts = $payload['texts'] ?? [];

        $projectContext = config('chief-hive.context.default', '');
        $systemContent = <<<'TXT'
Je bent een professionele vertaler die consistente, natuurlijke en contextueel correcte vertalingen levert.
Je taak: vul enkel ontbrekende of lege vertalingen in.
- Behoud bestaande teksten exact zoals ze zijn.
- Behoud placeholders en speciale tekens zoals "#" exact.
- Output ALTIJD een JSON array van objecten.
- De array moet exact dezelfde lengte, volgorde en keys hebben als de input.
- Elke entry in de array moet dezelfde keys bevatten als in de input (bv. "nl", "fr").
- Output mag niets anders bevatten dan de JSON array.
TXT;

        $userContent = 'Vertaal de volgende inhoud naar de missende locales: '
            .implode(',', ChiefSites::locales())
            .'. Hier is de input: '
            .json_encode($texts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        //        $systemContent = 'Je bent een professionele vertaler die consistente, natuurlijke en contextueel correcte vertalingen levert. Output ALTIJD in exact dezelfde datastructuur als de input.
        // Output altijd als JSON object. Gebruik de broninhoud en de meegegeven context om terminologie en tone of voice correct te houden. Hier is een beetje context over de site waarin de afbeelding gebruikt wordt: '.$projectContext;
        //        $userContent = 'Vertaal de volgende tekst(en) naar de locales: '.implode(',', ChiefSites::locales()).'. Geef de output terug in JSON, waarbij de structuur en keys identiek blijven. Vertaal enkel de ontbrekende,lege teksten. laat bestaande teksten onveranderd, Laat placeholders, speciale tekens zoals "#" onveranderd. Hier is de te vertalen inhoud: '.json_encode($texts, JSON_UNESCAPED_UNICODE);

        $response = app(OpenAiDriver::class)->chat([
            'model' => config('chief-hive.openai.model', 'gpt-4o'),
            //            'reasoning' => ['effort' => 'low'],
            'temperature' => 0.2,
            'messages' => [
                ['role' => 'system', 'content' => $systemContent],
                ['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => $userContent],
                ]],
            ],
            'response_format' => null,
            'max_tokens' => 8000,
        ]);

        $content = $response->toArray()['choices'][0]['message']['content'];

        if (! $content) {
            throw new \Exception('No content returned from OpenAI for texts '.implode(',', $texts));
        }

        $result = json_decode($content, true);

        if (! $result || ! is_array($result)) {
            throw new \Exception('Invalid JSON returned from OpenAI for texts '.implode(',', $texts));
        }

        $this->result = $result;

        return $this;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}

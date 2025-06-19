<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers;

use OpenAI;

class OpenAiDriver implements Driver
{
    public function chat($payload): OpenAI\Responses\Chat\CreateResponse
    {
        $client = OpenAI::client(config('chief-hive.openai.api_key'));

        /** @var OpenAI\Responses\Chat\CreateResponseMessage $response */
        return $client->chat()->create($payload);

        //        return new HivePromptResponse(
        //            $response->toArray()['choices'][0]['message']['content'],
        //            $response->toArray()
        //        );

        //            'model' => config('chief-hive.openai.model'),
        //            'messages' => [
        //                ['role' => 'system', 'content' => 'Je bent een seo assistent die alt-teksten genereert voor afbeeldingen.'],
        //                ['role' => 'user', 'content' => [
        //                    ['type' => 'text', 'text' => 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst, zowel in NL als FR. Output als JSON met de keys "nl" en "fr".'],
        //                    ['type' => 'image_url', 'image_url' => [
        //                        'url' => 'data:image/jpeg;base64,'.$imageData,
        //                    ]],
        //                ]],
        //            ],
        //            'response_format' => ['type' => 'json_object'],
        //            'max_tokens' => 4000,

        //        $content = $response->toArray()['choices'][0]['message']['content'];
        //        $alts = json_decode($content, true);
    }
}

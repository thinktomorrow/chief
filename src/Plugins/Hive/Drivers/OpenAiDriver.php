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
    }
}

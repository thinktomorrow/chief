<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Thinktomorrow\Chief\Management\Exceptions\MissingAssistant;

trait AssistedManager
{
    protected $assistants = [];

    /**
     * Check if this manager is assisted by a certain assistant
     *
     * @param string $assistantKey
     * @return bool
     */
    public function isAssistedBy(string $assistantKey): bool
    {
        return !! $this->getAssistantClass($assistantKey);
    }

    /**
     * @param bool $asInstances
     * @return array
     * @throws \Exception
     */
    public function assistants($asInstances = true): array
    {
        $assistants = [];

        foreach ($this->assistants as $assistant) {
            $assistants[] = $asInstances ? $this->assistant($assistant) : $assistant;
        }

        return $assistants;
    }

    public function assistantsAsClassNames()
    {
        return $this->assistants(false);
    }

    /**
     * Instantiate the assistant
     *
     * @param string $assistantKey
     * @return Assistant
     * @throws \Exception
     */
    public function assistant(string $assistantKey): Assistant
    {
        if (! $this->isAssistedBy($assistantKey)) {
            throw new MissingAssistant('No assistant [' . $assistantKey . '] registered on manager ' . get_class($this));
        }

        $instance = app($this->getAssistantClass($assistantKey));
        $instance->manager($this);

        return $instance;
    }

    /**
     * Get assistant class by key or assistant classname
     *
     * @param string $assistantKey
     * @return string|null
     */
    private function getAssistantClass(string $assistantKey): ?string
    {
        foreach ($this->assistants as $class) {
            if ($assistantKey == $class::key()) {
                return $class;
            }
        }

        // Alternatively, check if the passed argument is the assistant class name
        if (in_array($assistantKey, $this->assistants)) {
            return $assistantKey;
        }

        return null;
    }

    public function addAssistant(string $assistantClass)
    {
        if(false === array_search($assistantClass, $this->assistants)) {
            $this->assistants[] = $assistantClass;
        }

        return $this;
    }
}

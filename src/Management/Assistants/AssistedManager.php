<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Thinktomorrow\Chief\Management\Exceptions\MissingAssistant;

trait AssistedManager
{
    protected $assistants = [];

    /**
     * Check if this manager is assisted by a certain assistant
     *
     * @param string $assistant
     * @return bool
     */
    public function isAssistedBy(string $assistant): bool
    {
        return !! $this->getAssistantClass($assistant);
    }

    public function assistants(): array
    {
        $assistants = [];

        foreach($this->assistants as $assistant){
            $assistants[] = $this->assistant($assistant);
        }

        return $assistants;
    }

    /**
     * Instantiate the assistant
     *
     * @param string $assistant
     * @return Assistant
     * @throws \Exception
     */
    public function assistant(string $assistant): Assistant
    {
        if (! $this->isAssistedBy($assistant)) {
            throw new MissingAssistant('No assistant [' . $assistant . '] present on manager ' . get_class($this));
        }

        $instance = app($this->getAssistantClass($assistant));
        $instance->manager($this);

        return $instance;
    }

    private function getAssistantClass($assistant): ?string
    {
        if (in_array($assistant, $this->assistants)) {
            return $assistant;
        }

        foreach ($this->assistants as $class) {
            if ($assistant == $class::key()) {
                return $class;
            }
        }

        return null;
    }
}

<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Setup\stubs;

final class GenerateAssistant
{
    public function handle()
    {
        // Which namespace?

        // Replace all stuff ...

        //
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel()],
            $stub
        );

        return $this;
    }
}

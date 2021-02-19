<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Filesystem\Filesystem;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;

final class ManagerFactory
{
    /** @var array */
    private $assistants;

    /** @var bool */
    private $withRegistration;

    /** @var string */
    private $managedModelClass;

    /** @var string */
    private $managerClass;

    /** @var string */
    private $namespace;

    /** @var string */
    private static $directory = __DIR__ . '/tmp/Managers';

    private function __construct(string $managerClass)
    {
        $this->managerClass = $managerClass;
        $this->namespace = "Thinktomorrow\\Chief\\Tests\\Shared\\Tmp\\Managers";

        $this->assistants = [ManagerDefaults::class];
        $this->withRegistration = true;
    }

    public static function make(string $managerClass = null): self
    {
        // We need a different classname each time because otherwise composer still refers to the first found class
        return new static($managerClass ?? 'FoobarManager' . mt_rand(1, 9999));
    }

    public static function clearTemporaryFiles()
    {
        if (! is_dir(static::$directory)) {
            return;
        }

        $filesystem = app(Filesystem::class);

        collect($filesystem->files(static::$directory, true))->each(function ($file) use ($filesystem) {
            $filesystem->delete($file);
        });
    }

    public function withAssistants(...$assistants): self
    {
        if (count($assistants) == 1 && is_array(reset($assistants))) {
            $assistants = reset($assistants);
        }

        $this->assistants = array_merge($this->assistants, $assistants);

        return $this;
    }

    public function create(): Manager
    {
        if (! is_dir(static::$directory)) {
            mkdir(static::$directory);
        }

        if (! $this->managedModelClass) {
            $model = ManagedModelFactory::make()->create();
            $this->managedModelClass = get_class($model);
        }

        file_put_contents(static::$directory.'/' . $this->managerClass. '.php', $this->managerContent());

        app(Register::class)->model($this->managedModelClass, $this->namespace . "\\" . $this->managerClass);

        return app(Registry::class)->manager($this->managedModelClass::managedModelKey());
    }

    public function withModel($model): self
    {
        $this->managedModelClass = is_string($model) ? $model : get_class($model);

        return $this;
    }

    private function managerContent(): string
    {
        $assistantStrings = '';
        foreach ($this->assistants as $assistant) {
            $assistantStrings .= 'use \\' . $assistant .';';
        }

        return <<<HEREDOC
<?php

namespace $this->namespace;

use \Thinktomorrow\Chief\Managers\Manager;
use \Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;

class $this->managerClass implements Manager
{
    use ManagerDefaults;
    $assistantStrings

    public static function routeKey(): string
    {
        return "managed-routekey";
    }

    public function managedModelClass(): string
    {
        return "$this->managedModelClass";
    }

    protected function adminLabels(): array
    {
        return [];
    }
}
HEREDOC;
    }
}

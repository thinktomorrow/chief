<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

final class ManagedModelFactory
{
    /** @var array */
    private $traits;

    /** @var array */
    private $fields = [];

    /** @var string */
    private $modelClass;

    /** @var string */
    private $namespace;

    /** @var string */
    private static $directory = __DIR__ . '/tmp/ManagedModels';

    private $withoutDatabaseInsert = false;
    private $dynamicKeys = [];
    private $translatedAttributes = [];

    private function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->namespace = "Thinktomorrow\\Chief\\Tests\\Shared\\Tmp\\ManagedModels";

        $this->traits = [
            FragmentableDefaults::class,
        ];
    }

    public static function make(string $modelClass = null): self
    {
        // We need a different classname each time because otherwise composer still refers to the first found class
        return new static($modelClass ?? 'FoobarModel' . mt_rand(1, 9999));
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

    public function withTraits(...$traits): self
    {
        if (count($traits) == 1 && is_array(reset($traits))) {
            $traits = reset($traits);
        }

        $this->traits = $traits;

        return $this;
    }

    public function withoutDatabaseInsert(): self
    {
        $this->withoutDatabaseInsert = true;

        return $this;
    }

    public function fields(array $fields = []): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function dynamicKeys(array $dynamicKeys = []): self
    {
        $this->dynamicKeys = $dynamicKeys;

        return $this;
    }

    public function translatedAttributes(array $translatedAttributes = []): self
    {
        $this->translatedAttributes = $translatedAttributes;

        return $this;
    }

    public function create($attributes = []): ManagedModel
    {
        if (! is_dir(static::$directory)) {
            mkdir(static::$directory);
        }

        file_put_contents(static::$directory.'/' . $this->modelClass. '.php', $this->content());

        static::migrateUp();

        $modelClass = $this->namespace . "\\" . $this->modelClass;
        $modelClass::$fields = $this->fields;

        // Create translation model
        if (count($this->translatedAttributes) > 0) {
            $translationFactory = ManagedTranslationModelFactory::make(class_basename($modelClass));

            if ($this->withoutDatabaseInsert) {
                $translationFactory->withoutDatabaseInsert();
            }

            $translationFactory->create();
        }

        $model = ! $this->withoutDatabaseInsert
            ? $modelClass::create($attributes)
            : new $modelClass($attributes);

        return $model;
    }

    private static function migrateUp()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('current_state')->default(PageState::DRAFT);
            $table->json('values')->nullable();
        });
    }

    private function content(): string
    {
        $traitStrings = '';
        foreach ($this->traits as $trait) {
            $traitStrings .= 'use \\' . $trait .';';
        }

        if (count($this->dynamicKeys) > 0) {
            $traitStrings .= 'use \\' . HasDynamicAttributes::class . ';';
        }

        if (count($this->translatedAttributes) > 0) {
            $traitStrings .= 'use \\' . Translatable::class . ';';
        }

        $dynamicKeysString = count($this->dynamicKeys) > 0
            ? '"' . implode('","', $this->dynamicKeys) . '"': null;

        $translatedAttributesString = count($this->translatedAttributes) > 0
            ? '"' . implode('","', $this->translatedAttributes) . '"': null;

        return <<<HEREDOC
<?php

namespace $this->namespace;

use \Thinktomorrow\Chief\ManagedModels\ManagedModel;
use \Thinktomorrow\Chief\Fragments\Fragmentable;
use \Thinktomorrow\Chief\ManagedModels\Assistants\SavingFields;
use \Thinktomorrow\Chief\ManagedModels\States\PageState;
use \Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;;
use Illuminate\Database\Eloquent\Model;
use \Thinktomorrow\AssetLibrary\HasAsset;
use \Thinktomorrow\AssetLibrary\AssetTrait;

class $this->modelClass extends Model implements ManagedModel, HasAsset, StatefulContract, Fragmentable
{
    public \$table = 'articles';
    public \$timestamps = false;
    public \$guarded = [];
    protected \$translationForeignKey = 'article_id';

    // Public property in order to easily inject custom fields in the test model.
    public static \$fields = [];

    use AssetTrait;
    use SavingFields;
    $traitStrings

    public \$dynamicKeys = [$dynamicKeysString];
    public \$translatedAttributes = [$translatedAttributesString];

    public function stateOf(\$key): string
    {
        return \$this->\$key ?? PageState::DRAFT;
    }

    public function changeStateOf(\$key, \$state)
    {
        if (\$state === \$this->stateOf(\$key)) {
            return;
        }

        PageState::assertNewState(\$this, \$key, \$state);

        \$this->\$key = \$state;
    }

    public static function managedModelKey(): string
    {
        return 'article';
    }

    public function fields(): Fields
    {
        return new Fields(static::\$fields);
    }

    public function adminLabel(string \$key, \$default = null, array \$replace = []): ?string
    {
        return '-';
    }
}
HEREDOC;
    }
}

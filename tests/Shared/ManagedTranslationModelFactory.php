<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class ManagedTranslationModelFactory
{
    /** @var string */
    private $modelClass;

    /** @var string */
    private $namespace;

    /** @var string */
    private static $directory = __DIR__ . '/tmp/ManagedModels';

    private $withoutDatabaseInsert = false;

    private function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->namespace = "Thinktomorrow\\Chief\\Tests\\Shared\\Tmp\\ManagedModels";
    }

    public static function make(string $modelClass): self
    {
        return new static($modelClass . 'Translation');
    }

    public function withoutDatabaseInsert(): self
    {
        $this->withoutDatabaseInsert = true;

        return $this;
    }

    public function create($attributes = [])
    {
        if (! is_dir(static::$directory)) {
            mkdir(static::$directory);
        }

        file_put_contents(static::$directory.'/' . $this->modelClass. '.php', $this->content());

        static::migrateUp();

        $modelClass = $this->namespace . "\\" . $this->modelClass;

        $model = ! $this->withoutDatabaseInsert
            ? $modelClass::create($attributes)
            : new $modelClass($attributes);

        return $model;
    }

    private static function migrateUp()
    {
        Schema::create('article_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->string('locale');
            $table->string('title_trans')->nullable();
            $table->text('content_trans')->nullable();
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    private function content(): string
    {
        return <<<HEREDOC
<?php

namespace $this->namespace;

use Illuminate\Database\Eloquent\Model;

class $this->modelClass extends Model
{
    public \$table = 'article_translations';
    public \$guarded = [];
}
HEREDOC;
    }
}

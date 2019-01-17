<?php

namespace Thinktomorrow\Chief\Tests;

use Illuminate\Filesystem\Filesystem;

trait ChiefDatabaseTransactions
{
    protected $connectionsToTransact = ['testing'];
    protected static $migrationsHaveRun = false;

    protected $testDatabasePath = __DIR__.'/../database/testing.sqlite';

    protected function setUpDatabase()
    {
        if (! self::$migrationsHaveRun) {
            $this->removeTestDatabase();

            touch($this->testDatabasePath);

            $chief_migrations = app(Filesystem::class)->allFiles(realpath(__DIR__.'/../database/migrations'));
            $assetlibrary_migrations = app(Filesystem::class)->allFiles(realpath(__DIR__.'/../vendor/thinktomorrow/assetlibrary/database/migrations'));
            $squanto_migrations = app(Filesystem::class)->allFiles(realpath(__DIR__.'/../vendor/thinktomorrow/squanto/database/migrations'));

            $migrations = array_merge($chief_migrations, $assetlibrary_migrations, $squanto_migrations);
            foreach ($migrations as $migration) {
                include_once $migration->getPathName();

                $class = $this->guessClassNameFromFile($migration->getPathName());
                (new $class)->up();
            }

            self::$migrationsHaveRun = true;
        }

        $this->beginDatabaseTransaction();
    }

    protected function removeTestDatabase()
    {
        if (file_exists($this->testDatabasePath)) {
            unlink($this->testDatabasePath);
        }
    }

    /**
     * Handle database transactions on the specified connections.
     * We don't use the laravel trait because here we can start
     * transactions after migration is run. This will greatly
     * increase the overall test speed.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $connection = $database->connection($name);

                $connection->rollBack();
                $connection->disconnect();
            }
        });
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }

    /**
     * @ref https://stackoverflow.com/questions/7153000/get-class-name-from-file
     * @param $file
     * @return string
     */
    private function guessClassNameFromFile($file)
    {
        $fp = fopen($file, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) {
                break;
            }

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) {
                continue;
            }

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j=$i+1;$j<count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\'.$tokens[$j][1];
                        } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                            break;
                        }
                    }
                }
            }
        }

        return $class;
    }
}

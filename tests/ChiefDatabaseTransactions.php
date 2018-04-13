<?php

namespace Tests;

trait ChiefDatabaseTransactions
{
    protected $connectionsToTransact = ['testing'];
    protected static $migrationsHaveRun = false;

    protected function setUpDatabase()
    {
        if( ! self::$migrationsHaveRun)
        {
            if(!file_exists(database_path('testing.sqlite')))
            {
                touch(database_path('testing.sqlite'));
            }

            $this->artisan('migrate:fresh');
            self::$migrationsHaveRun = true;
        }

        $this->beginDatabaseTransaction();
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

}
<?php

namespace Thinktomorrow\Chief\Setup;

class Setup
{
    public function run()
    {
        foreach ($this->tasks() as $task){
            $this->runTask($task);
        }
    }

    private function runTask(Task $task)
    {
        if($task->check()) return;

        $task->run();
    }

    /**
     * @return Task[]
     */
    private function tasks(): array
    {
        return [

        ];
    }
}
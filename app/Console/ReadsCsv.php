<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\Console;

trait ReadsCsv
{
    private function loop(string $path, $callback)
    {
        if (($handle = fopen($path, "r")) !== false) {
            while (($data = fgetcsv($handle, 4000, ",")) !== false) {
                call_user_func_array($callback, [$data]);
            }
        }

        fclose($handle);
    }
}

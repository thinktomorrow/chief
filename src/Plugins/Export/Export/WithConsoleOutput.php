<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Symfony\Component\Console\Style\SymfonyStyle;

trait WithConsoleOutput
{
    protected ?SymfonyStyle $output = null;

    public function setOutput(SymfonyStyle $output): static
    {
        $this->output = $output;

        return $this;
    }

    public function writeOutput(string $message, ?string $type = null): void
    {
        if (! $this->output) {
            return;
        }

        if ($type === 'info') {
            $this->output->info($message);
        } elseif ($type === 'error') {
            $this->output->error($message);
        } elseif ($type === 'warning') {
            $this->output->warning($message);
        } else {
            $this->output->writeln($message);
        }
    }
}

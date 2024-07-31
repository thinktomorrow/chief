<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Managers\Register\Registry;

class ExportAllCommand extends BaseCommand
{
    protected $signature = 'chief:export-all';

    protected $description = 'Export all models, menu and static texts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $resourceKeys = array_keys(app(Registry::class)->pageResources());

        foreach ($resourceKeys as $resourceKey) {
            try {
                $this->call('chief:export-resource', [
                    'resource' => $resourceKey,
                ]);
            } catch (\Exception $e) {
                $this->error('Failed to export resource ' . $resourceKey .'. Reason: ' . $e->getMessage());
            }
        }

        $this->call('chief:export-menu');
        $this->call('chief:export-text');
    }
}

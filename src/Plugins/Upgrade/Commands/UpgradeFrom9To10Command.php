<?php

namespace Thinktomorrow\Chief\Plugins\Upgrade\Commands;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Fragments\App\Sections\HasBookmark;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Models\ModelDefaults;
use Thinktomorrow\Chief\Models\Page;
use Thinktomorrow\Chief\Models\PageDefaults;
use Thinktomorrow\Chief\Models\ShowsPageState;
use Thinktomorrow\Chief\Plugins\Upgrade\ListProjectFiles;
use Thinktomorrow\Chief\Plugins\Upgrade\ReplaceTextInFile;

class UpgradeFrom9To10Command extends BaseCommand
{
    protected $signature = 'chief:upgrade-from-9-to-10';

    protected $description = 'Facilitate the upgrade from Chief 0.9 to Chief 0.10';

    private ListProjectFiles $listProjectFiles;

    private ReplaceTextInFile $replaceTextInFile;

    public function __construct(ListProjectFiles $listProjectFiles, ReplaceTextInFile $replaceTextInFile)
    {
        parent::__construct();
        $this->listProjectFiles = $listProjectFiles;
        $this->replaceTextInFile = $replaceTextInFile;
    }

    public function handle(): void
    {
        $this->info('Chief 0.10 introduces breaking changes in code structure and fragment classes. View the changelog for more details. This command will guide you through the upgrade process.');

        if (! $this->confirm('Are you sure you want to proceed?')) {
            $this->info('Upgrade process aborted.');

            return;
        }

        // Get all files in the project directories: app, config, bootstrap, database, resources, routes, tests
        // Exclude the vendor, node_modules and public files
        $files = $this->listProjectFiles->get();

        //        $this->textReplacements($files);

        $result = $this->textOccurrences($files);

        while (! $result && $this->confirm('Please refactor manually and check again', true)) {
            $result = $this->textOccurrences($files);
        }

        $this->info('Upgrade process completed.');
    }

    private function textReplacements(array $files): void
    {
        $textReplacements = [

            // Fragment
            'Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults' => BaseFragment::class,
            'Thinktomorrow\Chief\Fragments\Fragmentable' => Fragment::class,
            'Thinktomorrow\Chief\Fragments\Assistants\HasBookmark' => HasBookmark::class,
            'use Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties;' => '',
            'use ForwardFragmentProperties;' => '',
            'use FragmentableDefaults;' => '',
            ' implements Fragment' => ' extends BaseFragment implements Fragment',
            ' implements Fragmentable' => ' extends BaseFragment implements Fragment',
            'fragmentModel()' => 'getFragmentModel()',
            'Thinktomorrow\Chief\ManagedModels\Presets\Fragment' => Fragment::class,

            // Page
            'Thinktomorrow\Chief\ManagedModels\Presets\Page' => Page::class,
            'Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults' => PageDefaults::class,
            'Thinktomorrow\Chief\ManagedModels\Assistants\ModelDefaults' => ModelDefaults::class,
            'Thinktomorrow\Chief\ManagedModels\Assistants\ShowsPageState' => ShowsPageState::class,
        ];

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            foreach ($textReplacements as $search => $replace) {
                $this->replaceTextInFile->replace($file->getRealPath(), $search, $replace);
            }
        }
    }

    private function textOccurrences(array $files): bool
    {
        $textOccurrences = [
            'renderFragment(' => 'The following files have the old renderFragment method. This method has been removed in Chief 0.10. Please replace it with render().',
            'renderAdminFragment(' => 'The following files have the old renderAdminFragment method. This method has been removed in Chief 0.10. Please replace it with renderInAdmin().',
        ];

        $allClean = true;

        /** @var \SplFileInfo $file */
        foreach ($textOccurrences as $search => $note) {

            $files = array_filter($files, function ($file) use ($search) {
                return strpos(file_get_contents($file->getRealPath()), $search) !== false;
            });

            if (count($files) > 0) {
                $this->info($note);
                $this->table(['File'], array_map(fn ($file) => [$file->getRealPath()], $files));
                $allClean = false;
            }
        }

        return $allClean;
    }
}

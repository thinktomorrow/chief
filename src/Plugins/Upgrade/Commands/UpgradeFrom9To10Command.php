<?php

namespace Thinktomorrow\Chief\Plugins\Upgrade\Commands;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\HasBookmark;
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

        // Perform text replacements
        $this->textReplacements($files);

        // Show manual refactorings
        $result = $this->textOccurrences($files);
        while (! $result && $this->confirm('Please refactor manually and check again', true)) {
            $result = $this->textOccurrences($files);
        }

        $this->info('Upgrade process completed.');
    }

    private function textReplacements(array $files): void
    {
        $this->info('Start with replacing texts');

        $textReplacements = [

            // Fragment
            'Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults' => BaseFragment::class,
            'Thinktomorrow\Chief\Fragments\Fragmentable' => Fragment::class,
            'Thinktomorrow\Chief\Fragments\Assistants\HasBookmark' => HasBookmark::class,
            'use Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties;' => '',
            'use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;' => '',
            'use Thinktomorrow\Chief\Fragments\FragmentsOwner;' => '',
            'use ForwardFragmentProperties;' => '',
            'use FragmentableDefaults;' => '',
            'use OwningFragments;' => '',
            'FragmentsOwner, ' => '',
            'FragmentsOwner ' => '',
            ' implements Fragment' => ' extends BaseFragment implements Fragment',
            ' implements Fragmentable' => ' extends BaseFragment implements Fragment',
            'extends BaseFragment extends BaseFragment' => 'extends BaseFragment',
            '->fragmentModel()' => '->getFragmentModel()',
            'Thinktomorrow\Chief\ManagedModels\Presets\Fragment' => Fragment::class,
            'private string $viewPath' => 'protected string $viewPath',
            'Thinktomorrow\Chief\Forms\Fields\Common\LocalizedFormKey' => 'Thinktomorrow\Chief\Forms\Fields\FieldName\FieldName',
            'LocalizedFormKey' => 'LocalizedFieldName',
            'Thinktomorrow\Chief\Forms\Fields\Repeat' => 'Thinktomorrow\Chief\Forms\Fields\Repeat\Repeat',

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

        $this->info('Text replacements completed');
    }

    private function textOccurrences(array $allFiles): bool
    {
        $textOccurrences = [
            'renderAdminFragment(' => 'The following files have the old renderAdminFragment method. This method has been removed in Chief 0.10. Please replace it with renderInAdmin().',
            'renderFragment(' => 'The following files have the old renderFragment method. This method has been removed in Chief 0.10. Please replace it with render().',
        ];

        $allClean = true;

        foreach ($textOccurrences as $search => $note) {

            /** @var \SplFileInfo $file */
            $files = array_filter($allFiles, function ($file) use ($search) {
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

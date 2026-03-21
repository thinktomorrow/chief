<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Database\Application\CacheDatabaseLines;
use Thinktomorrow\Squanto\Database\Application\UpdateDatabaseLine;
use Thinktomorrow\Squanto\Database\DatabaseLinesRepository;
use Thinktomorrow\Squanto\Manager\Http\ManagerController;
use Thinktomorrow\Squanto\Manager\Pages\LineViewModel;
use Thinktomorrow\Squanto\Manager\Pages\PagesRepository;

class TranslationController extends ManagerController
{
    use AuthorizesRequests;

    private const SEARCH_SESSION_KEY = 'squanto.search_term';

    private PagesRepository $pagesRepository;

    private DatabaseLinesRepository $databaseLinesRepository;

    public function __construct(
        PagesRepository $pagesRepository,
        DatabaseLinesRepository $databaseLinesRepository,
        UpdateDatabaseLine $updateDatabaseLine,
        CacheDatabaseLines $cacheDatabaseLines,
    ) {
        parent::__construct($pagesRepository, $databaseLinesRepository, $updateDatabaseLine, $cacheDatabaseLines);

        $this->pagesRepository = $pagesRepository;
        $this->databaseLinesRepository = $databaseLinesRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('view-squanto');

        if ($request->boolean('reset')) {
            $request->session()->forget(self::SEARCH_SESSION_KEY);

            return redirect()->route('squanto.index');
        }

        $searchTerm = trim((string) $request->query('search', ''));

        if ($request->has('search') && $searchTerm === '') {
            $request->session()->forget(self::SEARCH_SESSION_KEY);

            return redirect()->route('squanto.index');
        }

        if ($request->has('search') && $searchTerm !== '') {
            $request->session()->put(self::SEARCH_SESSION_KEY, $searchTerm);
        }

        if (! $request->has('search')) {
            $searchTerm = (string) $request->session()->get(self::SEARCH_SESSION_KEY, '');
        }

        $pages = $this->pagesRepository->all();

        if ($searchTerm === '') {
            return view('squanto::index', [
                'pages' => $pages->all(),
            ]);
        }

        $results = $this->databaseLinesRepository->search($searchTerm)
            ->map(fn ($line) => new LineViewModel($line))
            ->values()
            ->all();

        return view('squanto::search', [
            'results' => $results,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function update(Request $request, $pageSlug)
    {
        $this->authorize('update-squanto');

        return parent::update($request, $pageSlug);
    }

    public function edit($pageSlug)
    {
        $this->authorize('update-squanto');

        return parent::edit($pageSlug);
    }
}

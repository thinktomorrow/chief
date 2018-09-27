<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferencePresenter;
use Thinktomorrow\Chief\Common\Relations\Relation;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Application\ArchivePage;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\PageCreateRequest;
use Thinktomorrow\Chief\Pages\Application\UpdatePage;
use Thinktomorrow\Chief\App\Http\Requests\PageUpdateRequest;
use Thinktomorrow\Chief\Pages\Application\DeletePage;
use Thinktomorrow\Chief\Sets\SetReference;
use Thinktomorrow\Chief\Sets\StoredSetReference;

class PagesController extends Controller
{
    public function index($collection)
    {
        $this->authorize('view-page');

        $model = Page::fromCollectionKey($collection);

        return view('chief::back.pages.index', [
            'page'              => $model,
            'collectionDetails' => $model->collectionDetails(),
            'published'         => $model->published()->paginate(10),
            'drafts'            => $model->drafted()->paginate(10),
            'archived'          => $model->archived()->paginate(10),
        ]);
    }

    public function create($collection)
    {
        $this->authorize('create-page');

        $page = Page::fromCollectionKey($collection);
        $page->existingRelationIds = collect([]);
        $relations = FlatReferencePresenter::toGroupedSelectValues(Relation::availableChildren($page))->toArray();

        return view('chief::back.pages.create', [
            'page'      => $page,
            'relations' => $relations,
            'images'    => $this->populateMedia($page),
        ]);
    }

    public function store(PageCreateRequest $request, $collection)
    {
        $this->authorize('create-page');

        $page = app(CreatePage::class)->handle(
            $collection,
            $request->trans
        );

        return redirect()->route('chief.back.pages.edit', $page->getKey())->with('messages.success', $page->title . ' is toegevoegd in draft. Happy editing!');
    }

    public function edit($id)
    {
        $this->authorize('update-page');

        $page = Page::findOrFail($id);
        $page->injectTranslationForForm();

        $page->existingRelationIds = FlatReferenceCollection::make($page->children())->toFlatReferences();

        $available = Relation::availableChildren($page);

        $available_modules     = FlatReferencePresenter::toGroupedSelectValues(Relation::availableChildrenOnlyModules($available))->toArray();
        $available_pages       = FlatReferencePresenter::toGroupedSelectValues(Relation::availableChildrenOnlyPages($available))->toArray();
        $available_sets        = FlatReferencePresenter::toGroupedSelectValues(Relation::availableChildrenOnlySets())->toArray();

        // Current sections
        $sections = $page->children()->map(function ($section, $index) {
            if ($section instanceof TranslatableContract) {
                $section->injectTranslationForForm();
            }

            return [
                // Module reference is by id.
                'id'         => $section->flatReference()->get(),

                // Key is a separate value to assign each individual module.
                // This is separate from id to avoid vue key binding conflicts.
                'key'        => $section->flatReference()->get(),
                'type'       => $this->guessSectionType($section),
                'slug'       => $section->slug,
                'sort'       => $index,
                'trans'      => $section->trans ?? [],
            ];
        })->toArray();

        return view('chief::back.pages.edit', [
            'page'               => $page,
            'sections'           => $sections,
            'available_modules'  => $available_modules,
            'available_pages'    => $available_pages,
            'available_sets'     => $available_sets,
            'images'             => $this->populateMedia($page),

            // Module collections for creating own page modules
            'module_collections' => Module::availableCollections()->values()->map->toArray()->toArray(),

        ]);
    }

    /**
     * Section type is the grouping inside the pagebuilder (specifically the menu)
     *
     * @param $section
     * @return string
     */
    private function guessSectionType($section)
    {
        if ($section instanceof ActsAsCollection && in_array($section->collectionKey(), ['text','pagetitle'])) {
            return $section->collectionKey();
        }

        if ($section instanceof StoredSetReference || $section instanceof SetReference) {
            // TODO: clean this up and replace 'pageset' with 'set';
            return 'pageset';
        }

        if ($section instanceof Page) {
            return 'page';
        }

        // We want all other types to be registered as modules
        return 'module';
    }

    public function update(PageUpdateRequest $request, $id)
    {
        $this->authorize('update-page');
        $page = app(UpdatePage::class)->handle(
            $id,
            $request->get('sections', []),
            $request->get('trans', []),
            $request->get('custom_fields', []),
            $request->get('relations', []),
            array_merge($request->get('files', []), $request->file('files', [])), // Images are passed as base64 strings, not as file, Documents are passed via the file segment
            $request->get('filesOrder', [])
        );

        return redirect()->route('chief.back.pages.edit', $page->id)->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $page->title . '" werd aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function archive($id)
    {
        $this->authorize('delete-page');

        $page = Page::find($id);

        app(ArchivePage::class)->handle($page->id);

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.warning', 'De pagina is gearchiveerd.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize('delete-page');

        if (request()->get('deleteconfirmation') !== 'DELETE') {
            return redirect()->back()->with('messages.warning', 'Je artikel is niet verwijderd. Probeer opnieuw');
        }

        $page = Page::withArchived()->find($id);

        app(DeletePage::class)->handle($page->id);

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.warning', 'De pagina is verwijderd.');
    }

    public function publish(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $published = true === !$request->checkboxStatus; // string comp. since bool is passed as string

        ($published) ? $page->publish() : $page->draft();

        return redirect()->back();
    }

    public function unpublish(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $published = true === !$request->checkboxStatus; // string comp. since bool is passed as string

        ($published) ? $page->publish() : $page->draft();

        return redirect()->back();
    }

    /**
     * @param $page
     * @return array
     */
    private function populateMedia($page): array
    {
        $images = array_fill_keys($page->mediaFields('type'), []);

        foreach ($page->getAllFiles()->groupBy('pivot.type') as $type => $assetsByType) {
            foreach ($assetsByType as $asset) {
                $images[$type][] = (object)[
                    'id'       => $asset->id,
                    'filename' => $asset->getFilename(),
                    'url'      => $asset->getFileUrl(),
                ];
            }
        }

        return $images;
    }
}

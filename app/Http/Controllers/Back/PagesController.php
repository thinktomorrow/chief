<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\PageCreateRequest;
use Thinktomorrow\Chief\Pages\Application\UpdatePage;
use Thinktomorrow\Chief\App\Http\Requests\PageUpdateRequest;

class PagesController extends Controller
{
    public function index($collection)
    {
        $this->authorize('view-page');

        $model = Page::fromCollectionKey($collection);

        return view('chief::back.pages.index', [
            'collectionDetails' => $model->collectionDetails(),
            'published'       => $model->published()->paginate(10),
            'drafts'          => $model->drafted()->paginate(10),
            'archived'        => $model->archived()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($collection)
    {
        $this->authorize('create-page');

        $page = Page::fromCollectionKey($collection);
        $page->existingRelationIds = collect([]);
        $relations = RelatedCollection::availableChildren($page)->flattenForGroupedSelect()->toArray();

        return view('chief::back.pages.create', [
            'page'            => $page,
            'relations'       => $relations,
            'images'          => $this->populateMedia($page),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(PageCreateRequest $request, $collection)
    {
        $this->authorize('create-page');

        $page = app(CreatePage::class)->handle(
            $collection,
            $request->trans,
            $request->relations,
            $request->get('files', []),
            $request->get('filesOrder', [])
        );

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.success', $page->title . ' is aangemaakt');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->authorize('update-page');

        $page = Page::ignoreCollection()->findOrFail($id);
        $page->injectTranslationForForm();

        $page->existingRelationIds = RelatedCollection::relationIds($page->children());
        $relations = RelatedCollection::availableChildren($page)->flattenForGroupedSelect()->toArray();

        return view('chief::back.pages.edit', [
            'page'            => $page,
            'relations'       => $relations,
            'images'          => $this->populateMedia($page),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(PageUpdateRequest $request, $id)
    {
        $this->authorize('update-page');

        $page = app(UpdatePage::class)->handle(
            $id,
            $request->trans,
            $request->relations,
            $request->get('files', []),
            $request->get('filesOrder', [])
        );

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $page->title . '" werd aangepast');
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

        $page = Page::ignoreCollection()->withArchived()->findOrFail($id);
        if (request()->get('deleteconfirmation') !== 'DELETE' && (!$page->isPublished() || $page->isArchived())) {
            return redirect()->back()->with('messages.warning', 'Je artikel is niet verwijderd. Probeer opnieuw');
        }

        if ($page->isDraft() || $page->isArchived()) {
            $page->delete();
        }
        if ($page->isPublished()) {
            $page->archive();
        }

        $message = 'Het item werd verwijderd.';

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.warning', $message);
    }

    public function publish(Request $request, $id)
    {
        $page = Page::ignoreCollection()->findOrFail($id);
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

        foreach ($page->getAllFiles()->groupBy('pivot.type') as $type => $assetsByType)
        {
            foreach ($assetsByType as $asset)
            {
                $images[$type][] = (object) [
                    'id'  => $asset->id, 'filename' => $asset->getFilename(),
                    'url' => $asset->getFileUrl()
                ];
            }
        }

        return $images;
    }
}

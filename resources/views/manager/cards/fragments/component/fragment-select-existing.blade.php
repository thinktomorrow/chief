<div class="space-y-12">
    <h3>Kies een bestaand fragment</h3>

    <form action="{{ $ownerManager->route('fragments-select-existing', $owner) }}">
        @if(public_method_exists($owner, 'getRelatedOwners'))
            <select name="owners[]" id="selectExistingOwners">
                <option value="">---</option>
                @foreach($owner->getRelatedOwners() as $relatedOwner)
                    <option value="{{ $relatedOwner->modelReference()->get() }}">{{ $relatedOwner->adminConfig()->getPageTitle() }}</option>
                @endforeach
            </select>
        @endif
        <select name="types[]" id="selectExistingTypes">
            <option value="">---</option>
            @foreach($owner->allowedFragments() as $allowedFragmentClass)
                <option value="{{ $allowedFragmentClass }}">{{ (new $allowedFragmentClass)->adminConfig()->getModelName() }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>

    <div data-sidebar-component="existingFragments" class="row-start-center gutter-1">

        @if(count($sharedFragments) > 0)
            <div class="space-x-2">
                @foreach($sharedFragments as $sharedFragment)
                    <div data-sidebar-close
                         data-fragments-add="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) . (isset($order) ? '?order=' . $order : '') }}"
                         class="mb-6 cursor-pointer"
                    >
                        <h4>{{ $sharedFragment['model']->adminConfig()->getPageTitle() }}</h4>
                        <div class="wireframe">
                            {!! $sharedFragment['model']->renderAdminFragment($owner, $loop) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>
                Geen fragmenten gevonden.
            </p>
        @endif

    </div>
</div>

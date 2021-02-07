<header class="bg-white border-b border-grey-100 sticky top-0 z-20">
    <div class="container">
        <div class="row stack flex justify-between">
            <div class="column-9">
                <h1 class="flex items-center mb-0">
                    @adminLabel('page_title')
                </h1>
            </div>

            <div class="column-3 text-right justify-end">
                @adminCan('create')
                    <a href="@adminRoute('create')" class="btn btn-secondary inline-flex items-center">
                        <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>
                        <span>Voeg een @adminLabel('label') toe</span>
                    </a>
                @endAdminCan
            </div>
        </div>
    </div>
</header>

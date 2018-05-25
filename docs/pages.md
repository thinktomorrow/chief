# Pages


# Work with pages
Each page model should basically require: routes and corresponding controllers and views.
We recommend creating a PageController to manage the different page endpoints. Here's an example:
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\Page;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        return view('pages.index', [
            'pages' => Page::all()
        ]);
    }

    public function show(Request $request)
    {
        return view('pages.index', [
            'page' => Page::findBySlug($request->slug)
        ]);
    }
}
``` 

Create the necessary routes for each endpoint. By default, we assume the page routing has the following naming convention:
`pages.index`, `pages.show` (TODO: elaborate)

## Page API
#### Page::all()

#### Page::findBySlug($slug)
Retrieve a page by its unique slug value.

#### Page::sortedByCreated()->all()
Sort the results by last created pages.

## Publishable API

#### Page::findPublishedBySlug($slug)
Retrieve a published page by its unique slug value.
If the page is not published, no page will be returned.

#### Page::sortedByPublished()->all()
Sort the results by last created pages.

## Create custom page

- routing
- model
- controllers
- views

- custom traits / behaviour -> bit extreme

-> easy setup for new page element.... chief:page <name>
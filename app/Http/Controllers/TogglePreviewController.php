<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\Http\Controllers;

use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

final class TogglePreviewController extends Controller
{
    public function toggle()
    {
        PreviewMode::toggle();

        return redirect()->back();
    }
}

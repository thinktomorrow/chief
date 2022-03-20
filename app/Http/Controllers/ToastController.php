<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\Http\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Site\AdminToast;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

final class ToastController extends Controller
{
    private AdminToast $adminToast;

    public function __construct(AdminToast $adminToast)
    {
        $this->adminToast = $adminToast;
    }

    public function toggle()
    {
        PreviewMode::toggle();

        return redirect()->back();
    }

    public function get(Request $request)
    {
        if(!chiefAdmin()) {
            return response()->json(['data' => null]);
        }

        $editUrl = $this->adminToast->discoverEditUrl(
            $request->input('path'),
            $request->input('locale')
        );

        return response()->json(['data' =>
            view('chief-site::admin-toast-element', [
                'editUrl' => $editUrl,
                'toggleUrl' => route('chief.toast.toggle'),
                'inPreviewMode' => (bool) $request->input('preview_mode', false),
            ])->render()
        ]);
    }
}

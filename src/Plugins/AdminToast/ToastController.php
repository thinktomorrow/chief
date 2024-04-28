<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\AdminToast;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

final class ToastController extends Controller
{
    private GuessEditUrl $guessEditUrl;

    public function __construct(GuessEditUrl $guessEditUrl)
    {
        $this->guessEditUrl = $guessEditUrl;
    }

    public function toggle()
    {
        PreviewMode::toggle();

        return redirect()->back();
    }

    public function get(Request $request)
    {
        if (! chiefAdmin()) {
            return response()->json(['data' => null]);
        }

        $editUrl = $this->guessEditUrl->guessByPathAndLocale(
            $request->input('path'),
            $request->input('locale'),
            $request->input('locale_segment')
        );

        $toastView = view('chief-admin-toast::element', [
            'editUrl' => $editUrl,
            'toggleUrl' => route('chief.toast.toggle'),
            'inPreviewMode' => (bool)$request->input('preview_mode', false),
        ])->render();

        return response()->json(['data' => $toastView]);
    }
}

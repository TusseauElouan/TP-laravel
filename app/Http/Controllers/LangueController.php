<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LangueController extends Controller
{
    /**
     * Change the application language.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request)
    {
        $lang = $request->input('lang');
        if (in_array($lang, ['en', 'fr'])) {
            Cookie::queue('locale', $lang, 60 * 24 * 365);
        }
        return redirect()->back();
    }
}

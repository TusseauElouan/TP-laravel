<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AccueilController extends Controller
{
    /**
     * Summary of index
     *
     * @return View
     */
    public function index()
    {
        return view('welcome');
    }
}

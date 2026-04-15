<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): RedirectResponse
    {
        if (auth()->user()?->hasAnyRole('admin', 'staff')) {
            return redirect()->route('reports.index');
        }

        return redirect()->route('catalog.index');
    }
}

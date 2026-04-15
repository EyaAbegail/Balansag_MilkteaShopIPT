<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->with(['drinks' => fn ($query) => $query
                ->where('is_available', true)
                ->orderByDesc('is_featured')
                ->orderBy('name')])
            ->orderBy('name')
            ->get();

        $featuredDrinks = $categories
            ->flatMap->drinks
            ->where('is_featured', true)
            ->take(3);

        $recentOrders = Auth::check()
            ? Order::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('catalog.index', compact('categories', 'featuredDrinks', 'recentOrders'));
    }
}

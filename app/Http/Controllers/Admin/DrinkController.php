<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Drink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DrinkController extends Controller
{
    public function index(): View
    {
        $drinks = Drink::query()->with('category')->latest()->paginate(10);

        return view('admin.drinks.index', compact('drinks'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.drinks.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDrink($request);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('drinks', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::lower(Str::random(5));

        Drink::create($validated);

        return redirect()->route('admin.drinks.index')->with('status', 'Drink added to the menu.');
    }

    public function show(Drink $drink): RedirectResponse
    {
        return redirect()->route('admin.drinks.edit', $drink);
    }

    public function edit(Drink $drink): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.drinks.edit', compact('drink', 'categories'));
    }

    public function update(Request $request, Drink $drink): RedirectResponse
    {
        $validated = $this->validateDrink($request);

        if ($request->hasFile('image')) {
            if ($drink->image_path) {
                Storage::disk('public')->delete($drink->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('drinks', 'public');
        }

        $drink->update($validated);

        return redirect()->route('admin.drinks.index')->with('status', 'Drink updated successfully.');
    }

    public function destroy(Drink $drink): RedirectResponse
    {
        if ($drink->image_path) {
            Storage::disk('public')->delete($drink->image_path);
        }

        $drink->delete();

        return redirect()->route('admin.drinks.index')->with('status', 'Drink removed from the menu.');
    }

    protected function validateDrink(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }
}

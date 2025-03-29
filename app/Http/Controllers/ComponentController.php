<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\ComponentCategory;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    /**
     * Display a listing of the components.
     */
    public function index()
    {
        $components = Component::with('category')->orderBy('name')->paginate(10);
        return view('admin.components.index', compact('components'));
    }

    /**
     * Show the form for creating a new component.
     */
    public function create()
    {
        $categories = ComponentCategory::orderBy('name')->get();
        return view('admin.components.create', compact('categories'));
    }

    /**
     * Store a newly created component in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:component_categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'specifications' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'serial_number' => 'nullable|string|max:255|unique:components',
            'status' => 'required|in:available,in_use,maintenance,retired',
        ]);

        Component::create($validated);

        return redirect()->route('components.index')
            ->with('success', 'Component created successfully!');
    }

    /**
     * Display the specified component.
     */
    public function show(Component $component)
    {
        $component->load('category', 'pcs.rentals');
        return view('admin.components.show', compact('component'));
    }

    /**
     * Show the form for editing the specified component.
     */
    public function edit(Component $component)
    {
        $categories = ComponentCategory::orderBy('name')->get();
        return view('admin.components.edit', compact('component', 'categories'));
    }

    /**
     * Update the specified component in storage.
     */
    public function update(Request $request, Component $component)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:component_categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'specifications' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'serial_number' => 'nullable|string|max:255|unique:components,serial_number,' . $component->id,
            'status' => 'required|in:available,in_use,maintenance,retired',
        ]);

        $component->update($validated);

        return redirect()->route('components.index')
            ->with('success', 'Component updated successfully!');
    }

    /**
     * Remove the specified component from storage.
     */
    public function destroy(Component $component)
    {
        // Check if component is in use in any PC
        if ($component->pcs()->exists()) {
            return redirect()->route('components.index')
                ->with('error', 'Cannot delete component that is associated with PCs!');
        }

        $component->delete();

        return redirect()->route('components.index')
            ->with('success', 'Component deleted successfully!');
    }
}
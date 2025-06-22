<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComponentCategory;
use Illuminate\Http\Request;

class ComponentCategoryController extends Controller
{
    /**
     * Display a listing of the component categories.
     */
    public function index()
    {
        // This view will use the Livewire ComponentCategoryTable
        return view('admin.component-categories.index');
    }

    /**
     * Show the form for creating a new component category.
     */
    public function create()
    {
        return view('admin.component-categories.create');
    }

    /**
     * Store a newly created component category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:component_categories',
            'description' => 'nullable|string',
        ]);

        ComponentCategory::create($validated);

        return redirect()->route('admin.component-categories.index')
            ->with('success', 'Component category created successfully!');
    }

    /**
     * Show the form for editing the specified component category.
     */
    public function edit(ComponentCategory $componentCategory)
    {
        // Load components relationship for displaying in the view
        $componentCategory->load('components');
        
        return view('admin.component-categories.edit', compact('componentCategory'));
    }

    /**
     * Update the specified component category in storage.
     */
    public function update(Request $request, ComponentCategory $componentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:component_categories,name,' . $componentCategory->id,
            'description' => 'nullable|string',
        ]);

        $componentCategory->update($validated);

        return redirect()->route('admin.component-categories.index')
            ->with('success', 'Component category updated successfully!');
    }

    /**
     * Remove the specified component category from storage.
     */
    public function destroy(ComponentCategory $componentCategory)
    {
        // Check if the category has components
        if ($componentCategory->components()->exists()) {
            return redirect()->route('admin.component-categories.index')
                ->with('error', 'Cannot delete category with associated components!');
        }

        $componentCategory->delete();

        return redirect()->route('admin.component-categories.index')
            ->with('success', 'Component category deleted successfully!');
    }
}
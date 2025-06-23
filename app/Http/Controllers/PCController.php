<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Component;
use App\Models\ComponentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PCController extends Controller
{
    /**
     * Display a listing of the PCs.
     */
    public function index()
    {
        $pcs = PC::orderBy('name')->paginate(10);
        return view('admin.pcs.index', compact('pcs'));
    }

    /**
     * Show the form for creating a new PC.
     */
    public function create()
    {
        // Get available components grouped by category
        $categories = ComponentCategory::with([
            'components' => function ($query) {
                $query->where('status', 'available');
            }
        ])->get();

        return view('admin.pcs.create', compact('categories'));
    }

    /**
     * Store a newly created PC in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:pcs',
            'description' => 'nullable|string',
            'rental_price_hourly' => 'required|numeric|min:0',
            'rental_price_daily' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,retired',
            'assembly_date' => 'required|date',
            'components' => 'required|array|min:1',
            'components.*' => 'required|exists:components,id',
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create PC
            $pc = PC::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'rental_price_hourly' => $validated['rental_price_hourly'],
                'rental_price_daily' => $validated['rental_price_daily'],
                'status' => $validated['status'],
                'assembly_date' => $validated['assembly_date'],
            ]);

            // Attach components
            $componentsData = [];
            foreach ($validated['components'] as $componentId) {
                $componentsData[$componentId] = [
                    'installation_date' => $validated['assembly_date'],
                    'notes' => 'Installed during initial assembly',
                ];

                // Update component status
                Component::where('id', $componentId)->update(['status' => 'in_use']);
            }

            $pc->components()->attach($componentsData);

            DB::commit();

            return redirect()->route('pcs.index')
                ->with('success', 'PC created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to create PC: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified PC.
     */
    public function show(PC $pc)
    {
        $pc->load('components.category', 'rentals.user');
        return view('admin.pcs.show', compact('pc'));
    }

    /**
     * Show the form for editing the specified PC.
     */
    public function edit(PC $pc)
    {
        $pc->load('components.category');

        // Get all components grouped by category
        $categories = ComponentCategory::with([
            'components' => function ($query) use ($pc) {
                $query->where('status', 'available')
                    ->orWhereHas('pcs', function ($q) use ($pc) {
                        $q->where('pcs.id', $pc->id);
                    });
            }
        ])->get();

        return view('admin.pcs.edit', compact('pc', 'categories'));
    }

    /**
     * Update the specified PC in storage.
     */
    public function update(Request $request, PC $pc)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:pcs,code,' . $pc->id,
            'description' => 'nullable|string',
            'rental_price_hourly' => 'required|numeric|min:0',
            'rental_price_daily' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,retired',
            'assembly_date' => 'required|date',
            'components' => 'required|array|min:1',
            'components.*' => 'required|exists:components,id',
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Update PC
            $pc->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'rental_price_hourly' => $validated['rental_price_hourly'],
                'rental_price_daily' => $validated['rental_price_daily'],
                'status' => $validated['status'],
                'assembly_date' => $validated['assembly_date'],
            ]);

            // Get current components
            $currentComponentIds = $pc->components->pluck('id')->toArray();

            // Find components to remove and add
            $newComponentIds = $validated['components'];
            $componentsToRemove = array_diff($currentComponentIds, $newComponentIds);
            $componentsToAdd = array_diff($newComponentIds, $currentComponentIds);

            // Remove old components
            $pc->components()->detach($componentsToRemove);

            // Update status of removed components
            Component::whereIn('id', $componentsToRemove)->update(['status' => 'available']);

            // Add new components
            $componentsData = [];
            foreach ($componentsToAdd as $componentId) {
                $componentsData[$componentId] = [
                    'installation_date' => now(),
                    'notes' => 'Added during PC update',
                ];

                // Update component status
                Component::where('id', $componentId)->update(['status' => 'in_use']);
            }

            $pc->components()->attach($componentsData);

            DB::commit();

            return redirect()->route('pcs.index')
                ->with('success', 'PC updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update PC: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified PC from storage.
     */
    public function destroy(PC $pc)
    {
        // Check if PC has active rentals
        if ($pc->rentals()->whereIn('status', ['active', 'pending'])->exists()) {
            return redirect()->route('pcs.index')
                ->with('error', 'Cannot delete PC with active rentals!');
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Get component IDs
            $componentIds = $pc->components->pluck('id')->toArray();

            // Detach all components
            $pc->components()->detach();

            // Update component status
            Component::whereIn('id', $componentIds)->update(['status' => 'available']);

            // Delete PC
            $pc->delete();

            DB::commit();

            return redirect()->route('pcs.index')
                ->with('success', 'PC deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('pcs.index')
                ->with('error', 'Failed to delete PC: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Livewire;

use App\Models\PC;
use App\Models\Component;
use App\Models\ComponentCategory;
use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\DB;

class PcComponentManagement extends LivewireComponent
{
    public $pc;
    public $categories = [];
    public $selectedComponents = [];
    public $availableComponents = [];

    public function mount(PC $pc)
    {
        $this->pc = $pc;
        $this->loadCategoriesAndComponents();
        $this->selectedComponents = $pc->components->pluck('id')->toArray();
    }

    protected function loadCategoriesAndComponents()
    {
        // Load categories with components that are either available or already in this PC
        $this->categories = ComponentCategory::with([
            'components' => function ($query) {
                $query->where('status', 'available')
                    ->orWhereHas('pcs', function ($q) {
                        $q->where('pcs.id', $this->pc->id);
                    });
            }
        ])->get();
        
        // Prepare a flat list of available components for validation
        $this->availableComponents = [];
        foreach ($this->categories as $category) {
            foreach ($category->components as $component) {
                $this->availableComponents[$component->id] = $component;
            }
        }
    }

    public function updateComponents()
    {
        $this->validate([
            'selectedComponents' => 'required|array|min:1',
            'selectedComponents.*' => 'required|exists:components,id',
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Get current components
            $currentComponentIds = $this->pc->components->pluck('id')->toArray();

            // Find components to remove and add
            $newComponentIds = $this->selectedComponents;
            $componentsToRemove = array_diff($currentComponentIds, $newComponentIds);
            $componentsToAdd = array_diff($newComponentIds, $currentComponentIds);

            // Remove old components
            $this->pc->components()->detach($componentsToRemove);

            // Update status of removed components
            Component::whereIn('id', $componentsToRemove)->update(['status' => 'available']);

            // Add new components
            $componentsData = [];
            foreach ($componentsToAdd as $componentId) {
                $componentsData[$componentId] = [
                    'installation_date' => now(),
                    'notes' => 'Added via component management',
                ];

                // Update component status
                Component::where('id', $componentId)->update(['status' => 'in_use']);
            }

            $this->pc->components()->attach($componentsData);

            DB::commit();

            // Refresh PC data
            $this->pc = PC::with('components')->find($this->pc->id);
            
            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'PC components updated successfully.',
                'icon' => 'success',
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to update components: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pc-component-management');
    }
}
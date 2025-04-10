<?php

namespace App\Livewire;

use App\Models\PC;
use App\Models\Component;
use App\Models\ComponentCategory;
use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PCForm extends LivewireComponent
{
    public $pcId;
    public $name = '';
    public $code = '';
    public $description = '';
    public $rental_price_hourly = 0;
    public $rental_price_daily = 0;
    public $status = 'available';
    public $assembly_date;
    public $selectedComponents = [];
    public $isEdit = false;
    
    public $categories = [];
    public $availableComponents = [];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50'],
            'description' => 'nullable|string',
            'rental_price_hourly' => 'required|numeric|min:0',
            'rental_price_daily' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,retired',
            'assembly_date' => 'required|date',
            'selectedComponents' => 'required|array|min:1',
            'selectedComponents.*' => 'required|exists:components,id',
        ];

        if ($this->isEdit) {
            $rules['code'][] = Rule::unique('pcs')->ignore($this->pcId);
        } else {
            $rules['code'][] = 'unique:pcs';
        }

        return $rules;
    }

    public function mount($pc = null)
    {
        // Set today's date as default
        $this->assembly_date = now()->format('Y-m-d');
        
        // Load categories and available components
        $this->loadCategoriesAndComponents($pc);
        
        if ($pc) {
            $this->pcId = $pc->id;
            $this->name = $pc->name;
            $this->code = $pc->code;
            $this->description = $pc->description;
            $this->rental_price_hourly = $pc->rental_price_hourly;
            $this->rental_price_daily = $pc->rental_price_daily;
            $this->status = $pc->status;
            $this->assembly_date = $pc->assembly_date->format('Y-m-d');
            $this->selectedComponents = $pc->components->pluck('id')->toArray();
            $this->isEdit = true;
        }
    }

    protected function loadCategoriesAndComponents($pc = null)
    {
        if ($pc) {
            // For editing, we want to show both available components and those already in this PC
            $this->categories = ComponentCategory::with([
                'components' => function ($query) use ($pc) {
                    $query->where('status', 'available')
                        ->orWhereHas('pcs', function ($q) use ($pc) {
                            $q->where('pcs.id', $pc->id);
                        });
                }
            ])->get();
        } else {
            // For new PC, show only available components
            $this->categories = ComponentCategory::with([
                'components' => function ($query) {
                    $query->where('status', 'available');
                }
            ])->get();
        }
        
        // Prepare a flat list of available components for validation
        $this->availableComponents = [];
        foreach ($this->categories as $category) {
            foreach ($category->components as $component) {
                $this->availableComponents[$component->id] = $component;
            }
        }
    }

    public function updatedSelectedComponents()
    {
        $this->validate([
            'selectedComponents' => 'required|array|min:1',
            'selectedComponents.*' => 'required|exists:components,id',
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Start a transaction
        DB::beginTransaction();

        try {
            $pcData = [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'rental_price_hourly' => $this->rental_price_hourly,
                'rental_price_daily' => $this->rental_price_daily,
                'status' => $this->status,
                'assembly_date' => $this->assembly_date,
            ];

            if ($this->isEdit) {
                // Update existing PC
                $pc = PC::findOrFail($this->pcId);
                $pc->update($pcData);
                
                // Get current components
                $currentComponentIds = $pc->components->pluck('id')->toArray();

                // Find components to remove and add
                $newComponentIds = $this->selectedComponents;
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
                
                // Success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'PC updated successfully.',
                    'icon' => 'success',
                ]);
                
                // Redirect to PC details
                DB::commit();
                return redirect()->route('admin.pcs.show', $pc);
                
            } else {
                // Create new PC
                $pc = PC::create($pcData);

                // Attach components
                $componentsData = [];
                foreach ($this->selectedComponents as $componentId) {
                    $componentsData[$componentId] = [
                        'installation_date' => $this->assembly_date,
                        'notes' => 'Installed during initial assembly',
                    ];

                    // Update component status
                    Component::where('id', $componentId)->update(['status' => 'in_use']);
                }

                $pc->components()->attach($componentsData);
                
                // Success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'PC created successfully.',
                    'icon' => 'success',
                ]);
                
                // Redirect to PC list
                DB::commit();
                return redirect()->route('admin.pcs.index');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to save PC: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pc-form');
    }
}
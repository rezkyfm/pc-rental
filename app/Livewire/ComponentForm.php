<?php

namespace App\Livewire;

use App\Models\Component;
use App\Models\ComponentCategory;
use Livewire\Component as LivewireComponent;

class ComponentForm extends LivewireComponent
{
    public $componentId;
    public $category_id;
    public $name = '';
    public $brand = '';
    public $model = '';
    public $specifications = '';
    public $purchase_price;
    public $purchase_date;
    public $serial_number = '';
    public $status = 'available';
    public $isEdit = false;

    protected $rules = [
        'category_id' => 'required|exists:component_categories,id',
        'name' => 'required|string|max:255',
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'specifications' => 'nullable|string',
        'purchase_price' => 'nullable|numeric|min:0',
        'purchase_date' => 'nullable|date',
        'serial_number' => 'nullable|string|max:255',
        'status' => 'required|in:available,in_use,maintenance,retired',
    ];

    public function mount($component = null)
    {
        if ($component) {
            $this->componentId = $component->id;
            $this->category_id = $component->category_id;
            $this->name = $component->name;
            $this->brand = $component->brand;
            $this->model = $component->model;
            $this->specifications = $component->specifications;
            $this->purchase_price = $component->purchase_price;
            $this->purchase_date = $component->purchase_date ? $component->purchase_date->format('Y-m-d') : null;
            $this->serial_number = $component->serial_number;
            $this->status = $component->status;
            $this->isEdit = true;
        } else {
            // Set default value for category_id if there are categories
            $firstCategory = ComponentCategory::first();
            if ($firstCategory) {
                $this->category_id = $firstCategory->id;
            }
        }
    }

    public function save()
    {
        if ($this->isEdit) {
            // Add unique validation for serial number on update
            $this->rules['serial_number'] = 'nullable|string|max:255|unique:components,serial_number,' . $this->componentId;
        } else {
            // Add unique validation for serial number on create
            $this->rules['serial_number'] = 'nullable|string|max:255|unique:components,serial_number';
        }

        $this->validate();

        try {
            $componentData = [
                'category_id' => $this->category_id,
                'name' => $this->name,
                'brand' => $this->brand,
                'model' => $this->model,
                'specifications' => $this->specifications,
                'purchase_price' => $this->purchase_price,
                'purchase_date' => $this->purchase_date,
                'serial_number' => $this->serial_number,
                'status' => $this->status,
            ];

            if ($this->isEdit) {
                $component = Component::findOrFail($this->componentId);
                $component->update($componentData);
                
                // SweetAlert2 success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'Component updated successfully.',
                    'icon' => 'success',
                ]);
                
                // Redirect to the component details page
                return redirect()->route('admin.components.show', $component);
            } else {
                $component = Component::create($componentData);
                
                // SweetAlert2 success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'Component created successfully.',
                    'icon' => 'success',
                ]);
                
                // Redirect to the components list
                return redirect()->route('admin.components.index');
            }
        } catch (\Exception $e) {
            // SweetAlert2 error message
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Error saving component: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $categories = ComponentCategory::orderBy('name')->get();
        
        return view('livewire.component-form', [
            'categories' => $categories
        ]);
    }
}
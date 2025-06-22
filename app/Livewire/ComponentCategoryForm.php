<?php

namespace App\Livewire;

use App\Models\ComponentCategory;
use Livewire\Component;

class ComponentCategoryForm extends Component
{
    public $categoryId;
    public $name = '';
    public $description = '';
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount($category = null)
    {
        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->isEdit = true;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                $category = ComponentCategory::findOrFail($this->categoryId);
                
                // Add unique validation for name on update
                $this->validate([
                    'name' => 'required|string|max:255|unique:component_categories,name,' . $this->categoryId,
                ]);
                
                $category->update([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);
                
                // SweetAlert2 success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'Component category updated successfully.',
                    'icon' => 'success',
                ]);
            } else {
                // Add unique validation for name on create
                $this->validate([
                    'name' => 'required|string|max:255|unique:component_categories,name',
                ]);
                
                ComponentCategory::create([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);
                
                // SweetAlert2 success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'Component category created successfully.',
                    'icon' => 'success',
                ]);
                
                $this->reset(['name', 'description']);
            }

            // Refresh the component list
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            // SweetAlert2 error message
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Error saving category: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.component-category-form');
    }
}
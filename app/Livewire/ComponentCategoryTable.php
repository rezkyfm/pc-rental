<?php

namespace App\Livewire;

use App\Models\ComponentCategory;
use Livewire\Component;
use Livewire\WithPagination;

class ComponentCategoryTable extends Component
{
    use WithPagination;
    
    // Set the theme for pagination
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $confirmingDelete = false;
    public $categoryToDelete = null;

    // Listen for events
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        // Using SweetAlert2 for confirmation instead of modal
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => "You won't be able to revert this!",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#d33',
            'cancelButtonColor' => '#3085d6',
            'confirmButtonText' => 'Yes, delete it!',
            'id' => $id
        ]);
    }

    public function deleteCategory($id)
    {
        $category = ComponentCategory::findOrFail($id);
        
        // Check if category has components
        if ($category->components()->exists()) {
            $this->dispatch('swal:error', [
                'title' => 'Cannot Delete!',
                'text' => 'This category has associated components.',
                'icon' => 'error',
            ]);
        } else {
            try {
                $category->delete();
                $this->dispatch('swal:success', [
                    'title' => 'Deleted!',
                    'text' => 'Category has been deleted successfully.',
                    'icon' => 'success',
                ]);
            } catch (\Exception $e) {
                $this->dispatch('swal:error', [
                    'title' => 'Error!',
                    'text' => 'Failed to delete category: ' . $e->getMessage(),
                    'icon' => 'error',
                ]);
            }
        }
    }

    public function render()
    {
        $categories = ComponentCategory::withCount('components')
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.component-category-table', [
            'categories' => $categories
        ]);
    }
}
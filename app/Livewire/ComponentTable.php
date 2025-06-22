<?php

namespace App\Livewire;

use App\Models\Component;
use Livewire\Component as LivewireComponent;
use Livewire\WithPagination;

class ComponentTable extends LivewireComponent
{
    use WithPagination;
    
    // Set the theme for pagination
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Listen for events
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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
        // Using SweetAlert2 for confirmation
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

    public function deleteComponent($id)
    {
        $component = Component::findOrFail($id);
        
        // Check if component is in use in any PC
        if ($component->pcs()->exists()) {
            $this->dispatch('swal:error', [
                'title' => 'Cannot Delete!',
                'text' => 'This component is associated with one or more PCs.',
                'icon' => 'error',
            ]);
        } else {
            try {
                $component->delete();
                $this->dispatch('swal:success', [
                    'title' => 'Deleted!',
                    'text' => 'Component has been deleted successfully.',
                    'icon' => 'success',
                ]);
            } catch (\Exception $e) {
                $this->dispatch('swal:error', [
                    'title' => 'Error!',
                    'text' => 'Failed to delete component: ' . $e->getMessage(),
                    'icon' => 'error',
                ]);
            }
        }
    }

    public function render()
    {
        $query = Component::with('category')
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('brand', 'like', '%' . $this->search . '%')
                      ->orWhere('model', 'like', '%' . $this->search . '%')
                      ->orWhere('serial_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                return $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            });

        // Get categories for filter
        $categories = \App\Models\ComponentCategory::orderBy('name')->get();
        
        // Get components with sorting
        $components = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.component-table', [
            'components' => $components,
            'categories' => $categories
        ]);
    }
}
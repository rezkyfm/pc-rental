<?php

namespace App\Livewire;

use App\Models\PC;
use Livewire\Component;
use Livewire\WithPagination;

class PCTable extends Component
{
    use WithPagination;
    
    // Set the theme for pagination
    protected $paginationTheme = 'tailwind';

    public $search = '';
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

    public function deletePC($id)
    {
        $pc = PC::findOrFail($id);
        
        // Check if PC has active rentals
        if ($pc->rentals()->whereIn('status', ['active', 'pending'])->exists()) {
            $this->dispatch('swal:error', [
                'title' => 'Cannot Delete!',
                'text' => 'This PC has active or pending rentals.',
                'icon' => 'error',
            ]);
            return;
        }

        // Start a transaction
        \DB::beginTransaction();

        try {
            // Get component IDs
            $componentIds = $pc->components->pluck('id')->toArray();

            // Detach all components
            $pc->components()->detach();

            // Update component status
            \App\Models\Component::whereIn('id', $componentIds)->update(['status' => 'available']);

            // Delete PC
            $pc->delete();

            \DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Deleted!',
                'text' => 'PC has been deleted successfully.',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to delete PC: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $query = PC::withCount('components')
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            });

        // Get PCs with sorting
        $pcs = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pc-table', [
            'pcs' => $pcs
        ]);
    }
}
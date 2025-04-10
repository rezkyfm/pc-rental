<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    
    // Set the theme for pagination
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $roleFilter = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Listen for events
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
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

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Check if user has active rentals
        if ($user->rentals()->whereIn('status', ['active', 'pending'])->exists()) {
            $this->dispatch('swal:error', [
                'title' => 'Cannot Delete!',
                'text' => 'This user has active or pending rentals.',
                'icon' => 'error',
            ]);
        } else {
            try {
                $user->delete();
                $this->dispatch('swal:success', [
                    'title' => 'Deleted!',
                    'text' => 'User has been deleted successfully.',
                    'icon' => 'success',
                ]);
            } catch (\Exception $e) {
                $this->dispatch('swal:error', [
                    'title' => 'Error!',
                    'text' => 'Failed to delete user: ' . $e->getMessage(),
                    'icon' => 'error',
                ]);
            }
        }
    }

    public function render()
    {
        $query = User::when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                return $query->where('role', $this->roleFilter);
            });

        // Get users with sorting
        $users = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.user-table', [
            'users' => $users
        ]);
    }
}
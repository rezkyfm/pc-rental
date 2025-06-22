<?php

namespace App\Livewire;

use App\Models\Rental;
use Livewire\Component;
use Livewire\WithPagination;

class RentalTable extends Component
{
    use WithPagination;
    
    // Set the theme for pagination
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statusFilter = '';
    public $userFilter = '';
    public $pcFilter = '';
    public $dateRangeStart = '';
    public $dateRangeEnd = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

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

    public function updatingUserFilter()
    {
        $this->resetPage();
    }

    public function updatingPcFilter()
    {
        $this->resetPage();
    }

    public function updatingDateRangeStart()
    {
        $this->resetPage();
    }

    public function updatingDateRangeEnd()
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

    public function cancelRental($id)
    {
        $rental = Rental::findOrFail($id);
        
        if (!in_array($rental->status, ['pending', 'active'])) {
            $this->dispatch('swal:error', [
                'title' => 'Cannot Cancel!',
                'text' => 'Only pending or active rentals can be cancelled.',
                'icon' => 'error',
            ]);
            return;
        }

        // Start a transaction
        \DB::beginTransaction();

        try {
            // Update rental status
            $rental->update(['status' => 'cancelled']);

            // If the rental was active, update PC status
            if ($rental->status === 'active') {
                $rental->pc->update(['status' => 'available']);
            }

            \DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Cancelled!',
                'text' => 'Rental has been cancelled successfully.',
                'icon' => 'success',
            ]);
            
            $this->dispatch('refreshComponent');
            
        } catch (\Exception $e) {
            \DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to cancel rental: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function confirmComplete($id)
    {
        $this->dispatch('openModal', 'complete-rental-form', ['rentalId' => $id]);
    }

    public function render()
    {
        $query = Rental::with(['user', 'pc'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($user) {
                          $user->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('pc', function ($pc) {
                          $pc->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('code', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->userFilter, function ($query) {
                return $query->where('user_id', $this->userFilter);
            })
            ->when($this->pcFilter, function ($query) {
                return $query->where('pc_id', $this->pcFilter);
            })
            ->when($this->dateRangeStart, function ($query) {
                return $query->where('start_time', '>=', $this->dateRangeStart);
            })
            ->when($this->dateRangeEnd, function ($query) {
                return $query->where('start_time', '<=', $this->dateRangeEnd . ' 23:59:59');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        // Get users and PCs for filters
        $users = \App\Models\User::orderBy('name')->get();
        $pcs = \App\Models\PC::orderBy('name')->get();
            
        // Get rentals with sorting
        $rentals = $query->paginate($this->perPage);

        return view('livewire.rental-table', [
            'rentals' => $rentals,
            'users' => $users,
            'pcs' => $pcs
        ]);
    }
}
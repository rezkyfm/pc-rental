<?php

namespace App\Livewire;

use App\Models\Maintenance;
use App\Models\PC;
use Livewire\Component;
use Livewire\WithPagination;

class MaintenanceTable extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $perPage = 10;
    public $sortField = 'scheduled_date';
    public $sortDirection = 'desc';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
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

    public function confirmCancel($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => "You are about to cancel this maintenance record!",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#d33',
            'cancelButtonColor' => '#3085d6',
            'confirmButtonText' => 'Yes, cancel it!',
            'id' => $id
        ]);
    }

    public function cancelMaintenance($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        
        if (!in_array($maintenance->status, ['scheduled', 'in_progress'])) {
            $this->dispatch('swal:error', [
                'title' => 'Cannot Cancel!',
                'text' => 'Only scheduled or in-progress maintenance can be cancelled.',
                'icon' => 'error',
            ]);
            return;
        }

        // Start a transaction
        \DB::beginTransaction();

        try {
            // Update maintenance record
            $maintenance->update(['status' => 'cancelled']);

            // If maintenance was in progress, check if there are other active maintenance records
            if ($maintenance->status === 'in_progress') {
                $otherMaintenance = Maintenance::where('pc_id', $maintenance->pc_id)
                    ->where('id', '!=', $maintenance->id)
                    ->where('status', 'in_progress')
                    ->exists();

                if (!$otherMaintenance) {
                    // If no other maintenance, set PC back to available
                    $maintenance->pc->update(['status' => 'available']);
                }
            }

            \DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Cancelled!',
                'text' => 'Maintenance has been cancelled successfully.',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to cancel maintenance: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $query = Maintenance::with(['pc', 'performer'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('pc', function ($subq) {
                          $subq->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('code', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                return $query->where('type', $this->typeFilter);
            });

        $maintenance = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.maintenance-table', [
            'maintenanceRecords' => $maintenance
        ]);
    }
}

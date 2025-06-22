<?php

namespace App\Livewire;

use App\Models\Maintenance;
use App\Models\PC;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompleteMaintenance extends Component
{
    public $maintenance_id;
    public $maintenance;
    public $completed_date;
    public $cost;
    public $notes;

    protected $rules = [
        'completed_date' => 'required|date',
        'cost' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount($maintenance)
    {
        $this->maintenance_id = $maintenance->id;
        $this->maintenance = $maintenance;
        $this->completed_date = Carbon::now()->format('Y-m-d\TH:i');
    }

    public function completeMaintenance()
    {
        $this->validate();

        // Start a transaction
        DB::beginTransaction();

        try {
            $maintenance = Maintenance::findOrFail($this->maintenance_id);
            
            if ($maintenance->status !== 'in_progress') {
                $this->dispatch('swal:error', [
                    'title' => 'Error!',
                    'text' => 'Only in-progress maintenance can be completed!',
                    'icon' => 'error',
                ]);
                return;
            }

            // Update maintenance record
            $maintenance->update([
                'completed_date' => $this->completed_date,
                'cost' => $this->cost,
                'description' => $maintenance->description . "\n\nCompletion Notes: " . ($this->notes ?? 'None'),
                'status' => 'completed',
            ]);

            // Check if there are other active maintenance records for this PC
            $otherMaintenance = Maintenance::where('pc_id', $maintenance->pc_id)
                ->where('id', '!=', $maintenance->id)
                ->where('status', 'in_progress')
                ->exists();

            if (!$otherMaintenance) {
                // If no other maintenance, set PC back to available
                $maintenance->pc->update(['status' => 'available']);
            }

            DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Maintenance completed successfully!',
                'icon' => 'success',
            ]);

            return redirect()->route('admin.maintenance.show', $maintenance);
            
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to complete maintenance: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.complete-maintenance');
    }
}

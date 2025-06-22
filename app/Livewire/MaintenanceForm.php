<?php

namespace App\Livewire;

use App\Models\Maintenance;
use App\Models\PC;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceForm extends Component
{
    public $pc_id;
    public $title;
    public $description;
    public $type = 'routine';
    public $scheduled_date;
    public $status = 'scheduled';
    public $performed_by;
    
    public $maintenance_id;
    public $isEdit = false;
    public $selectedPC = null;
    
    // For completion form
    public $completed_date;
    public $cost;
    public $notes;

    protected $rules = [
        'pc_id' => 'required|exists:pcs,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'type' => 'required|in:routine,repair,upgrade,other',
        'scheduled_date' => 'required|date',
        'status' => 'required|in:scheduled,in_progress',
        'performed_by' => 'required|exists:users,id',
    ];

    public function mount($maintenance = null, $selectedPcId = null)
    {
        // Set default values
        $this->scheduled_date = Carbon::now()->format('Y-m-d\TH:i');
        $this->performed_by = Auth::id();
        
        // If editing existing maintenance
        if ($maintenance) {
            $this->maintenance_id = $maintenance->id;
            $this->pc_id = $maintenance->pc_id;
            $this->title = $maintenance->title;
            $this->description = $maintenance->description;
            $this->type = $maintenance->type;
            $this->scheduled_date = Carbon::parse($maintenance->scheduled_date)->format('Y-m-d\TH:i');
            $this->status = $maintenance->status;
            $this->performed_by = $maintenance->performed_by;
            $this->isEdit = true;
            
            if ($maintenance->completed_date) {
                $this->completed_date = Carbon::parse($maintenance->completed_date)->format('Y-m-d\TH:i');
            }
            
            $this->cost = $maintenance->cost;
            $this->selectedPC = PC::find($maintenance->pc_id);
        }
        
        // If PC ID is provided (from another page)
        if ($selectedPcId && !$this->isEdit) {
            $this->pc_id = $selectedPcId;
            $this->selectedPC = PC::find($selectedPcId);
        }
    }

    public function updatedPcId()
    {
        if ($this->pc_id) {
            $this->selectedPC = PC::find($this->pc_id);
        } else {
            $this->selectedPC = null;
        }
    }

    public function createMaintenance()
    {
        $this->validate();

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create maintenance record
            $maintenance = Maintenance::create([
                'pc_id' => $this->pc_id,
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'scheduled_date' => $this->scheduled_date,
                'status' => $this->status,
                'performed_by' => $this->performed_by,
            ]);

            // If status is in_progress, update PC status
            if ($this->status === 'in_progress') {
                PC::where('id', $this->pc_id)->update(['status' => 'maintenance']);
            }

            DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Maintenance record created successfully!',
                'icon' => 'success',
            ]);

            return redirect()->route('admin.maintenance.show', $maintenance);
            
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to create maintenance record: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function updateMaintenance()
    {
        // Validate with different rules for editing
        $this->validate([
            'pc_id' => 'required|exists:pcs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:routine,repair,upgrade,other',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'performed_by' => 'required|exists:users,id',
            'completed_date' => 'nullable|date|after_or_equal:scheduled_date',
            'cost' => 'nullable|numeric|min:0',
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            $maintenance = Maintenance::findOrFail($this->maintenance_id);
            $oldStatus = $maintenance->status;
            $newStatus = $this->status;

            // Update maintenance record
            $maintenance->update([
                'pc_id' => $this->pc_id,
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'scheduled_date' => $this->scheduled_date,
                'completed_date' => $this->completed_date,
                'status' => $this->status,
                'cost' => $this->cost,
                'performed_by' => $this->performed_by,
            ]);

            // Handle PC status changes based on maintenance status
            if ($oldStatus !== $newStatus) {
                $pc = PC::findOrFail($this->pc_id);

                if ($newStatus === 'in_progress') {
                    // If maintenance is now in progress, set PC to maintenance
                    $pc->update(['status' => 'maintenance']);
                } elseif ($newStatus === 'completed' || $newStatus === 'cancelled') {
                    // If maintenance is completed or cancelled, check if PC is in maintenance
                    if ($pc->status === 'maintenance') {
                        // Check if there are other active maintenance records
                        $otherMaintenance = Maintenance::where('pc_id', $this->pc_id)
                            ->where('id', '!=', $this->maintenance_id)
                            ->where('status', 'in_progress')
                            ->exists();

                        if (!$otherMaintenance) {
                            // If no other maintenance, set PC back to available
                            $pc->update(['status' => 'available']);
                        }
                    }
                }
            }

            DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Maintenance record updated successfully!',
                'icon' => 'success',
            ]);

            return redirect()->route('admin.maintenance.show', $maintenance);
            
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to update maintenance record: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function completeMaintenance()
    {
        $this->validate([
            'completed_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

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
        $pcs = PC::orderBy('name')->get();
        $staff = User::whereIn('role', ['admin', 'operator'])->orderBy('name')->get();
        
        return view('livewire.maintenance-form', [
            'pcs' => $pcs,
            'staff' => $staff,
        ]);
    }
}

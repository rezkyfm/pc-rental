<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\PC;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the maintenance records.
     */
    public function index()
    {
        $maintenance = Maintenance::with(['pc', 'performer'])
            ->latest('scheduled_date')
            ->paginate(10);

        return view('admin.maintenance.index', compact('maintenance'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create(Request $request)
    {
        $pcId = $request->query('pc_id');
        $pc = null;

        if ($pcId) {
            $pc = PC::findOrFail($pcId);
        } else {
            $pcs = PC::orderBy('name')->get();
        }

        $staff = User::whereIn('role', ['admin', 'operator'])->orderBy('name')->get();

        return view('admin.maintenance.create', compact('pc', 'pcs', 'staff'));
    }

    /**
     * Store a newly created maintenance record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_id' => 'required|exists:pcs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:routine,repair,upgrade,other',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:scheduled,in_progress',
            'performed_by' => 'nullable|exists:users,id',
        ]);

        // Default to current user if performed_by is not set
        if (empty($validated['performed_by'])) {
            $validated['performed_by'] = Auth::id();
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create maintenance record
            $maintenance = Maintenance::create($validated);

            // If status is in_progress, update PC status
            if ($validated['status'] === 'in_progress') {
                PC::where('id', $validated['pc_id'])->update(['status' => 'maintenance']);
            }

            DB::commit();

            return redirect()->route('admin.maintenance.show', $maintenance)
                ->with('success', 'Maintenance record created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to create maintenance record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified maintenance record.
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->load('pc.components.category', 'performer');
        return view('admin.maintenance.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit(Maintenance $maintenance)
    {
        $pcs = PC::orderBy('name')->get();
        $staff = User::whereIn('role', ['admin', 'operator'])->orderBy('name')->get();

        return view('admin.maintenance.edit', compact('maintenance', 'pcs', 'staff'));
    }

    /**
     * Update the specified maintenance record in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'pc_id' => 'required|exists:pcs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:routine,repair,upgrade,other',
            'scheduled_date' => 'required|date',
            'completed_date' => 'nullable|date|after_or_equal:scheduled_date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'required|exists:users,id',
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            $oldStatus = $maintenance->status;
            $newStatus = $validated['status'];
            $pcId = $validated['pc_id'];

            // Update maintenance record
            $maintenance->update($validated);

            // Handle PC status changes based on maintenance status
            if ($oldStatus !== $newStatus) {
                $pc = PC::findOrFail($pcId);

                if ($newStatus === 'in_progress') {
                    // If maintenance is now in progress, set PC to maintenance
                    $pc->update(['status' => 'maintenance']);
                } elseif ($newStatus === 'completed' || $newStatus === 'cancelled') {
                    // If maintenance is completed or cancelled, check if PC is in maintenance
                    if ($pc->status === 'maintenance') {
                        // Check if there are other active maintenance records
                        $otherMaintenance = Maintenance::where('pc_id', $pcId)
                            ->where('id', '!=', $maintenance->id)
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

            return redirect()->route('admin.maintenance.show', $maintenance)
                ->with('success', 'Maintenance record updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update maintenance record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Complete the specified maintenance record.
     */
    public function complete(Request $request, Maintenance $maintenance)
    {
        if ($maintenance->status !== 'in_progress') {
            return redirect()->back()
                ->with('error', 'Only in-progress maintenance can be completed!');
        }

        $validated = $request->validate([
            'completed_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Update maintenance record
            $maintenance->update([
                'completed_date' => $validated['completed_date'],
                'cost' => $validated['cost'],
                'description' => $maintenance->description . "\n\nCompletion Notes: " . ($validated['notes'] ?? 'None'),
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

            return redirect()->route('admin.maintenance.show', $maintenance)
                ->with('success', 'Maintenance completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to complete maintenance: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified maintenance record.
     */
    public function cancel(Maintenance $maintenance)
    {
        if (!in_array($maintenance->status, ['scheduled', 'in_progress'])) {
            return redirect()->back()
                ->with('error', 'Only scheduled or in-progress maintenance can be cancelled!');
        }

        // Start a transaction
        DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('admin.maintenance.index')
                ->with('success', 'Maintenance cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to cancel maintenance: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Livewire;

use App\Models\PC;
use Livewire\Component;

class PcAvailabilityWidget extends Component
{
    public $availablePCs = [];
    public $rentedPCs = [];
    public $maintenancePCs = [];
    public $retiredPCs = [];
    
    public function mount()
    {
        $this->loadPCData();
    }
    
    public function loadPCData()
    {
        $this->availablePCs = PC::where('status', 'available')
            ->orderBy('name')
            ->get();
            
        $this->rentedPCs = PC::where('status', 'rented')
            ->with(['rentals' => function($query) {
                $query->where('status', 'active')
                    ->with('user')
                    ->latest();
            }])
            ->orderBy('name')
            ->get()
            ->map(function($pc) {
                $pc->current_rental = $pc->rentals->first(); // Get the most recent active rental
                return $pc;
            });
            
        $this->maintenancePCs = PC::where('status', 'maintenance')
            ->orderBy('name')
            ->get();
            
        $this->retiredPCs = PC::where('status', 'retired')
            ->orderBy('name')
            ->get();
    }
    
    public function render()
    {
        return view('livewire.pc-availability-widget');
    }
}
<?php

namespace App\Livewire;

use App\Models\Rental;
use App\Models\PC;
use App\Models\Payment;
use Livewire\Component;
use Carbon\Carbon;

class RentalStats extends Component
{
    public $period = 'today';
    public $customStart;
    public $customEnd;
    
    public $totalRentals = 0;
    public $activeRentals = 0;
    public $completedRentals = 0;
    public $totalRevenue = 0;
    public $topPCs = [];
    
    public function mount()
    {
        $this->customStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->customEnd = Carbon::now()->format('Y-m-d');
        $this->calculateStats();
    }
    
    public function updatedPeriod()
    {
        $this->calculateStats();
    }
    
    public function updatedCustomStart()
    {
        if ($this->period === 'custom') {
            $this->calculateStats();
        }
    }
    
    public function updatedCustomEnd()
    {
        if ($this->period === 'custom') {
            $this->calculateStats();
        }
    }
    
    protected function calculateStats()
    {
        // Determine date range based on period
        $startDate = null;
        $endDate = Carbon::now();
        
        switch ($this->period) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'last7days':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                break;
            case 'last30days':
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                break;
            case 'thisMonth':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'lastMonth':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'custom':
                $startDate = Carbon::parse($this->customStart)->startOfDay();
                $endDate = Carbon::parse($this->customEnd)->endOfDay();
                break;
        }
        
        // Calculate statistics
        $this->totalRentals = Rental::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $this->activeRentals = Rental::where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $this->completedRentals = Rental::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $this->totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');
            
        // Get top 5 most rented PCs in the period
        $this->topPCs = PC::select('pcs.id', 'pcs.name', 'pcs.code')
            ->selectRaw('COUNT(rentals.id) as rental_count')
            ->selectRaw('SUM(rentals.total_price) as total_revenue')
            ->join('rentals', 'pcs.id', '=', 'rentals.pc_id')
            ->whereBetween('rentals.created_at', [$startDate, $endDate])
            ->groupBy('pcs.id', 'pcs.name', 'pcs.code')
            ->orderByDesc('rental_count')
            ->limit(5)
            ->get();
    }
    
    public function render()
    {
        return view('livewire.rental-stats');
    }
}
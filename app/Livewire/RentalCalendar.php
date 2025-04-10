<?php

namespace App\Livewire;

use App\Models\PC;
use App\Models\Rental;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RentalCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $calendarData = [];
    public $availablePCsCount = [];
    public $showDetails = false;
    public $selectedDate = null;
    public $selectedDateRentals = [];
    public $selectedDateAvailablePCs = [];
    
    public function mount()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->generateCalendarData();
    }
    
    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendarData();
    }
    
    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendarData();
    }
    
    public function goToCurrentMonth()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->generateCalendarData();
    }
    
    private function generateCalendarData()
    {
        $this->calendarData = [];
        $this->availablePCsCount = [];
        
        // Get the first day of the month and total number of days
        $firstDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $lastDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth();
        $daysInMonth = $lastDay->day;
        
        // Get all PCs
        $totalPCs = PC::count();
        
        // Get all rentals for this month
        $rentals = Rental::with('pc')
            ->whereIn('status', ['active', 'pending'])
            ->where(function($query) use ($firstDay, $lastDay) {
                $query->whereBetween('start_time', [$firstDay->startOfDay(), $lastDay->endOfDay()])
                    ->orWhereBetween('end_time', [$firstDay->startOfDay(), $lastDay->endOfDay()])
                    ->orWhere(function($q) use ($firstDay, $lastDay) {
                        $q->where('start_time', '<', $firstDay)
                           ->where('end_time', '>', $lastDay);
                    });
            })
            ->get();
        
        // Group rentals by date
        $rentalsByDate = [];
        
        // Initialize all days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
            $formattedDate = $date->format('Y-m-d');
            $rentalsByDate[$formattedDate] = [];
            
            // Each PC is initially available
            $this->availablePCsCount[$formattedDate] = $totalPCs;
        }
        
        // Fill in rentals
        foreach ($rentals as $rental) {
            $startDate = Carbon::parse($rental->start_time);
            $endDate = Carbon::parse($rental->end_time);
            
            // Adjust to match month boundaries if needed
            if ($startDate->lt($firstDay)) {
                $startDate = $firstDay->copy();
            }
            
            if ($endDate->gt($lastDay)) {
                $endDate = $lastDay->copy();
            }
            
            // Add rental to each day from start_time to end_time
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $formattedDate = $currentDate->format('Y-m-d');
                
                if (isset($rentalsByDate[$formattedDate])) {
                    // Add rental to this date if not already added
                    if (!isset($rentalsByDate[$formattedDate][$rental->pc_id])) {
                        $rentalsByDate[$formattedDate][$rental->pc_id] = $rental;
                        
                        // Decrease available PCs count
                        $this->availablePCsCount[$formattedDate]--;
                    }
                }
                
                $currentDate->addDay();
            }
        }
        
        // Calculate calendar grid (including previous/next month days to fill grid)
        $startDayOfWeek = $firstDay->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        
        // Handle Sunday as first day of week (0 should become 7 days back from current week)
        $daysToSubtract = $startDayOfWeek === 0 ? 0 : $startDayOfWeek;
        
        // Add days from previous month
        $previousMonthDays = [];
        for ($i = $daysToSubtract - 1; $i >= 0; $i--) {
            $previousDate = $firstDay->copy()->subDays($i + 1);
            $previousMonthDays[] = [
                'day' => $previousDate->day,
                'month' => $previousDate->month,
                'year' => $previousDate->year,
                'formattedDate' => $previousDate->format('Y-m-d'),
                'isCurrentMonth' => false,
                'isToday' => $previousDate->isToday(),
            ];
        }
        
        // Current month days
        $currentMonthDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
            $formattedDate = $date->format('Y-m-d');
            
            $currentMonthDays[] = [
                'day' => $day,
                'month' => $this->currentMonth,
                'year' => $this->currentYear,
                'formattedDate' => $formattedDate,
                'isCurrentMonth' => true,
                'isToday' => $date->isToday(),
                'rentals' => $rentalsByDate[$formattedDate] ?? [],
                'rentalCount' => count($rentalsByDate[$formattedDate] ?? []),
                'availablePCs' => $this->availablePCsCount[$formattedDate] ?? 0,
            ];
        }
        
        // Combine all days
        $this->calendarData = array_merge($previousMonthDays, $currentMonthDays);
        
        // Add next month days to complete the grid (ensure we have full weeks)
        $totalDaysAdded = count($this->calendarData);
        $remainingDays = 42 - $totalDaysAdded; // 42 = 6 weeks × 7 days
        
        if ($remainingDays > 0) {
            for ($i = 1; $i <= $remainingDays; $i++) {
                $nextDate = $lastDay->copy()->addDays($i);
                $this->calendarData[] = [
                    'day' => $nextDate->day,
                    'month' => $nextDate->month,
                    'year' => $nextDate->year,
                    'formattedDate' => $nextDate->format('Y-m-d'),
                    'isCurrentMonth' => false,
                    'isToday' => $nextDate->isToday(),
                ];
            }
        }
    }
    
    public function viewDayDetails($date)
    {
        $this->selectedDate = $date;
        $this->showDetails = true;
        
        // Get rentals for this day
        $dayStart = Carbon::parse($date)->startOfDay();
        $dayEnd = Carbon::parse($date)->endOfDay();
        
        $this->selectedDateRentals = Rental::with(['pc', 'user'])
            ->whereIn('status', ['active', 'pending'])
            ->where(function($query) use ($dayStart, $dayEnd) {
                $query->whereBetween('start_time', [$dayStart, $dayEnd])
                    ->orWhereBetween('end_time', [$dayStart, $dayEnd])
                    ->orWhere(function($q) use ($dayStart, $dayEnd) {
                        $q->where('start_time', '<', $dayStart)
                           ->where('end_time', '>', $dayStart);
                    });
            })
            ->get();
        
        // Get all rented PC IDs for this day
        $rentedPcIds = $this->selectedDateRentals->pluck('pc_id')->unique()->toArray();
        
        // Get available PCs
        $this->selectedDateAvailablePCs = PC::whereNotIn('id', $rentedPcIds)
            ->where('status', 'available')
            ->get();
    }
    
    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedDate = null;
    }
    
    public function render()
    {
        return view('livewire.rental-calendar');
    }
}
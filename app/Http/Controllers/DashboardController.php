<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Rental;
use App\Models\User;
use App\Models\Component;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        // Count statistics
        $stats = [
            'total_pcs' => PC::count(),
            'available_pcs' => PC::where('status', 'available')->count(),
            'rented_pcs' => PC::where('status', 'rented')->count(),
            'maintenance_pcs' => PC::where('status', 'maintenance')->count(),

            'total_components' => Component::count(),
            'available_components' => Component::where('status', 'available')->count(),

            'total_customers' => User::where('role', 'customer')->count(),

            'active_rentals' => Rental::where('status', 'active')->count(),
            'pending_rentals' => Rental::where('status', 'pending')->count(),

            'upcoming_maintenance' => Maintenance::where('status', 'scheduled')
                ->where('scheduled_date', '>=', now())
                ->count(),
        ];

        // Revenue charts
        $monthlyRevenue = Rental::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Recent rentals
        $recentRentals = Rental::with(['user', 'pc'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming maintenance
        $upcomingMaintenance = Maintenance::with(['pc', 'performer'])
            ->where('status', 'scheduled')
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        // PC utilization (most rented)
        $pcUtilization = PC::withCount([
            'rentals' => function ($query) {
                $query->where('status', 'completed');
            }
        ])
            ->orderByDesc('rentals_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'recentRentals',
            'upcomingMaintenance',
            'pcUtilization'
        ));
    }
}
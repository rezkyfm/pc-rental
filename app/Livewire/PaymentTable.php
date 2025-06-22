<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class PaymentTable extends Component
{
    use WithPagination;
    
    // Set the theme for pagination
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $typeFilter = '';
    public $methodFilter = '';
    public $statusFilter = '';
    public $dateRangeStart = '';
    public $dateRangeEnd = '';
    public $perPage = 10;
    public $sortField = 'payment_date';
    public $sortDirection = 'desc';

    // Listen for events
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingMethodFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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

    public function confirmRefund($id)
    {
        $this->dispatch('openModal', 'refund-payment-form', ['paymentId' => $id]);
    }

    public function updateStatus($id, $status)
    {
        $payment = Payment::findOrFail($id);
        
        // Don't allow status change for refunds
        if ($payment->type === 'refund') {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Refund payment status cannot be changed.',
                'icon' => 'error',
            ]);
            return;
        }
        
        try {
            $payment->update(['status' => $status]);
            
            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Payment status updated successfully.',
                'icon' => 'success',
            ]);
            
            $this->dispatch('refreshComponent');
            
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to update payment status: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function mount()
    {
        // Set default date range to current month
        if (empty($this->dateRangeStart)) {
            $this->dateRangeStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        
        if (empty($this->dateRangeEnd)) {
            $this->dateRangeEnd = Carbon::now()->format('Y-m-d');
        }
    }

    public function render()
    {
        $query = Payment::with(['rental.user', 'rental.pc'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('payment_number', 'like', '%' . $this->search . '%')
                      ->orWhere('notes', 'like', '%' . $this->search . '%')
                      ->orWhereHas('rental', function ($rental) {
                          $rental->where('invoice_number', 'like', '%' . $this->search . '%')
                                ->orWhereHas('user', function ($user) {
                                    $user->where('name', 'like', '%' . $this->search . '%')
                                        ->orWhere('email', 'like', '%' . $this->search . '%');
                                });
                      });
                });
            })
            ->when($this->typeFilter, function ($query) {
                return $query->where('type', $this->typeFilter);
            })
            ->when($this->methodFilter, function ($query) {
                return $query->where('method', $this->methodFilter);
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->dateRangeStart, function ($query) {
                return $query->whereDate('payment_date', '>=', $this->dateRangeStart);
            })
            ->when($this->dateRangeEnd, function ($query) {
                return $query->whereDate('payment_date', '<=', $this->dateRangeEnd);
            })
            ->orderBy($this->sortField, $this->sortDirection);
            
        // Get payments with sorting
        $payments = $query->paginate($this->perPage);
        
        // Calculate totals
        $totals = [
            'total' => $query->sum('amount'),
            'deposit' => $query->clone()->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'rental' => $query->clone()->where('type', 'rental')->where('status', 'completed')->sum('amount'),
            'extra' => $query->clone()->where('type', 'extra')->where('status', 'completed')->sum('amount'),
            'refund' => $query->clone()->where('type', 'refund')->where('status', 'completed')->sum('amount'),
        ];

        return view('livewire.payment-table', [
            'payments' => $payments,
            'totals' => $totals
        ]);
    }
}
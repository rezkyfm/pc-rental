<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $rental->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info div {
            width: 48%;
        }
        .invoice-info h3 {
            margin: 0 0 10px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            color: #2563eb;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section table {
            margin-left: auto;
            width: 250px;
        }
        .total-section table td {
            padding: 5px;
        }
        .total-section table tr.total td {
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: none;
        }
        .payment-info {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .status-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-badge.active {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-badge.completed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-badge.cancelled {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>INVOICE</h1>
            <p>{{ $companyName }}</p>
            <p>{{ $companyAddress }}, {{ $companyCity }}, {{ $companyPostalCode }}, {{ $companyCountry }}</p>
            <p>Tax ID: {{ $companyTaxId }}</p>
        </div>
        
        <!-- Invoice Info Section -->
        <div class="invoice-info">
            <div>
                <h3>Bill To</h3>
                <p><strong>{{ $rental->user->name }}</strong></p>
                <p>{{ $rental->user->email }}</p>
                <p>{{ $rental->user->phone ?? 'No phone provided' }}</p>
            </div>
            <div>
                <h3>Invoice Details</h3>
                <p><strong>Invoice Number:</strong> {{ $rental->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoiceDate }}</p>
                <p><strong>Status:</strong> <span class="status-badge {{ $rental->status }}">{{ ucfirst($rental->status) }}</span></p>
            </div>
        </div>
        
        <!-- Rental Details Section -->
        <div class="invoice-details">
            <h3>Rental Details</h3>
            <table>
                <tr>
                    <th>PC Information</th>
                    <th>Rental Period</th>
                    <th>Rate</th>
                    <th>Duration</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>
                        <strong>{{ $rental->pc->name }}</strong><br>
                        Code: {{ $rental->pc->code }}<br>
                        {{ Str::limit($rental->pc->description, 50) }}
                    </td>
                    <td>
                        <strong>Start:</strong> {{ $startDate }}<br>
                        <strong>End:</strong> {{ $endDate }}<br>
                        @if($rental->actual_return_time)
                            <strong>Returned:</strong> {{ $returnDate }}
                        @endif
                    </td>
                    <td>{{ $currencySymbol }} {{ number_format($hourlyRate, 0, ',', '.') }}/hour</td>
                    <td>{{ $duration }} hours</td>
                    <td>{{ $currencySymbol }} {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Payment Details Section -->
        <div class="payment-details">
            <h3>Payment Details</h3>
            <table>
                <tr>
                    <th>Payment #</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Amount</th>
                </tr>
                @if($depositPayment)
                <tr>
                    <td>{{ $depositPayment->payment_number }}</td>
                    <td>{{ $depositPayment->payment_date->format('M d, Y H:i') }}</td>
                    <td>Deposit</td>
                    <td>{{ ucfirst($depositPayment->method) }}</td>
                    <td>{{ $currencySymbol }} {{ number_format($depositPayment->amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($rentalPayment)
                <tr>
                    <td>{{ $rentalPayment->payment_number }}</td>
                    <td>{{ $rentalPayment->payment_date->format('M d, Y H:i') }}</td>
                    <td>Final Payment</td>
                    <td>{{ ucfirst($rentalPayment->method) }}</td>
                    <td>{{ $currencySymbol }} {{ number_format($rentalPayment->amount, 0, ',', '.') }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- Total Section -->
        <div class="total-section">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>{{ $currencySymbol }} {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Deposit:</td>
                    <td>{{ $currencySymbol }} {{ number_format($rental->deposit_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total:</td>
                    <td>{{ $currencySymbol }} {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Payment Info -->
        <div class="payment-info">
            <h3>Notes</h3>
            <p>{{ $rental->notes ?? 'No additional notes.' }}</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>{{ $footerText }}</p>
            <p>This invoice was generated automatically. Please keep for your records.</p>
        </div>
    </div>
</body>
</html>

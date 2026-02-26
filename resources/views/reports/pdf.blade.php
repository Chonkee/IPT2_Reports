<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report: {{ $report->month_name }} {{ $report->year }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Report</h1>
        <p><strong>Month:</strong> {{ $report->month_name }} {{ $report->year }}</p>
        <p><strong>Generated on:</strong> {{ $report->created_at->format('M d, Y H:i') }}</p>
    </div>

    @if($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Account</th>
                    <th>Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('M d, Y H:i') }}</td>
                    <td>{{ $transaction->account->customer->name }}</td>
                    <td>{{ $transaction->account->account_number }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ $transaction->type === 'disbursement' ? '' : '₱' . number_format($transaction->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="4" style="text-align: right;">Total Payments</td>
                    <td>₱{{ number_format($transactions->where('type', 'payment')->sum('amount'), 2) }}</td>
                </tr>
            </tbody>
        </table>
        <p class="text-left"><strong>Total Transactions:</strong> {{ $transactions->count() }}</p>
    @else
        <p>No transactions found for this month.</p>
    @endif
</body>
</html>

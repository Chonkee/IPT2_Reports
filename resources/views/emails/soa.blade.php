<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { border: 1px solid #ddd; padding: 20px; border-radius: 0 0 5px 5px; }
        .section { margin: 20px 0; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Statement of Account</h1>
        </div>
        <div class="content">
            <p>Dear {{ $account->customer->name }},</p>
            
            <p>Please find your Statement of Account (SOA) details below:</p>
            
            <div class="section">
                <h3>Account Information:</h3>
                <p><span class="label">Account Number:</span> <span class="value">{{ $account->account_number }}</span></p>
                <p><span class="label">Principal Amount:</span> <span class="value">₱ {{ number_format($account->principal_amount, 2) }}</span></p>
                <p><span class="label">Interest Rate:</span> <span class="value">{{ $account->interest_rate }}%</span></p>
                <p><span class="label">Term:</span> <span class="value">{{ $account->term_months }} months</span></p>
                <p><span class="label">Monthly Payment:</span> <span class="value">₱ {{ number_format($account->monthly_payment, 2) }}</span></p>
                <p><span class="label">Total Amount:</span> <span class="value">₱ {{ number_format($account->total_amount, 2) }}</span></p>
            </div>
            
            <div class="section">
                <h3>Account Status:</h3>
                <p><span class="label">Current Balance:</span> <span class="value">₱ {{ number_format($account->balance, 2) }}</span></p>
                <p><span class="label">Status:</span> <span class="value">{{ ucfirst($account->status) }}</span></p>
                <p><span class="label">Start Date:</span> <span class="value">{{ $account->start_date->format('F d, Y') }}</span></p>
                <p><span class="label">Maturity Date:</span> <span class="value">{{ $account->maturity_date->format('F d, Y') }}</span></p>
            </div>
            
            @if($account->notes)
                <div class="section">
                    <h3>Notes:</h3>
                    <p>{{ $account->notes }}</p>
                </div>
            @endif
            
            <div class="footer">
                <p>Thank you for your business!</p>
                <p>If you have any questions, please contact us.</p>
            </div>
        </div>
    </div>
</body>
</html>

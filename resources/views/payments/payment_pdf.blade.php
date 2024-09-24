<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            width: 100%;
            margin-bottom: 30px;
        }
        .details td {
            padding: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .expenses {
            margin-top: 20px;
            width: 100%;
        }
        .expenses th, .expenses td {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Receipt</h1>
    </div>
    <table class="details">
        <tr>
            <td><strong>Client Name:</strong></td>
            <td>{{ $user['title'] }}</td>
        </tr>
        <tr>
            <td><strong>Invoice Hash:</strong></td>
            <td>{{ $hash  }}</td>
        </tr>
        <tr>
            <td><strong>Project Name:</strong></td>
            <td>{{ $project['title'] ?? 'Unknown' }}</td>
        </tr>
        <tr>
            <td><strong>Invoice Date:</strong></td>
            <td>{{ $invoice_date }}</td>
        </tr>
        <tr>
            <td><strong>Due Date:</strong></td>
            <td>{{ $due_date }}</td>
        </tr>
        <tr>
            <td><strong>Total Budget:</strong></td>
            <td>{{ $currency->sign }}{{ $amount }}</td>
        </tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td>{{ $status }}</td>
        </tr>
    </table>

    <h3>Project Expenses</h3>
    <table class="expenses">
        <thead>
            <tr>
                <th>Expense ID</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project_expenses as $expense)
                <tr>
                    <td>{{ $expense['id'] }}</td>
                    <td>{{ $expense['description'] }}</td>
                    <td>{{ $expense['qty'] }}</td>
                    <td>{{ $currency->sign }}{{ $expense['amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for your payment!</p>
    </div>
</body>
</html>

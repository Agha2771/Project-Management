<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Receipt</h1>
    </div>
    <table class="details">
        <tr>
            <td><strong>Client Name:</strong></td>
            <td>{{ $client_name }}</td>
        </tr>
        <tr>
            <td><strong>Project Name:</strong></td>
            <td>{{ $project_name }}</td>
        </tr>
        <tr>
            <td><strong>Total Budget:</strong></td>
            <td>{{$currency}}{{ $total_budget }}</td>
        </tr>
        <tr>
            <td><strong>Amount Paid:</strong></td>
            <td>{{$currency}}{{ $amount_paid }}</td>
        </tr>
        <tr>
            <td><strong>Remaining Balance:</strong></td>
            <td>{{$currency}}{{ $remaining_balance }}</td>
        </tr>
    </table>
    <div class="footer">
        <p>Thank you for your payment!</p>
    </div>
</body>
</html>

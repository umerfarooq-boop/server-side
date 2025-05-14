<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        .invoice {
            background: #fff;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }



        .left-header {
            flex: 1;
        }

        .right-header {
            text-align: right;
        }

        .logo {
            height: 50px;
        }

        .invoice-details p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
            max-width: 200px;
            word-wrap: break-word;
        }

        h1.invoice-title {
            color: #4B0082;
            margin: 20px 0 0;
            font-size: 28px;
        }

        .info-box {
            background-color: #f0f4ff;
            border-left: 5px solid #4B0082;
            padding: 15px 20px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .info-box h2 {
            margin-top: 0;
            color: #4B0082;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
        }

        .items {
            margin-top: 30px;
        }

        .items table {
            width: 100%;
            border-collapse: collapse;
        }

        .items th, .items td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .items th {
            background-color: #4B0082;
            color: #fff;
        }

        .total-label, .total-amount {
            font-weight: bold;
            text-align: right;
        }

        .invoice-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="invoice">
        {{-- stripe-svgrepo-com --}}
        <!-- Header with Logo and Invoice Details -->
        @php
    $imagePath = public_path('uploads/image/stripe-svgrepo-com.png');
@endphp

<header class="invoice-header" style="text-align: right; margin-top: 30px;">
    <div>
        <img src="file://{{ $imagePath }}" alt="Stripe Logo" class="logo" style="max-width: 150px;">
    </div>
    <div style="margin-top: 10px;">
        <p><strong>Invoice #:</strong> {{ Str::limit($payment_id, 5) }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
    </div>
</header>

        

        <!-- Invoice Title -->
        <h1 class="invoice-title">Invoice</h1>

        <!-- Player Details -->
        <section class="info-box">
            <h2>Player Details</h2>
            <p><strong>Name:</strong> {{ $player->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $player->email ?? 'N/A' }}</p>
        </section>

        <!-- Coach Details -->
        <section class="info-box">
            <h2>Coach Details</h2>
            <p><strong>Name:</strong> {{ $coach->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $coach->email ?? 'N/A' }}</p>
        </section>

        <!-- Payment Info -->
        <section class="items">
            <h2 style="color: #4B0082;">Payment Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount (PKR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Coaching Session Payment</td>
                        <td>{{ $amount }} PKR</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="total-label">Total Paid</td>
                        <td class="total-amount">{{ $amount }} PKR</td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <!-- Footer -->
        <footer class="invoice-footer">
            <p>Thank you for choosing our platform!</p>
        </footer>
    </div>

</body>
</html>

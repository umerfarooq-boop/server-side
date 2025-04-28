<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 20px; }
        .invoice { background: #fff; padding: 30px; max-width: 800px; margin: auto; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .invoice-header .logo { height: 50px; }
        .invoice-details { text-align: right; }
        .customer-info, .billing-info, .items { margin-top: 30px; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th, .items td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .items th { background: #f0f0f0; }
        .total-label { text-align: right; font-weight: bold; }
        .total-amount { text-align: right; }
        .invoice-footer { margin-top: 30px; text-align: center; font-size: 14px; color: #777; }
    </style>
</head>
<body>

<div class="invoice">
    <header class="invoice-header">
        <img src="https://stripe.com/img/v3/home/logos/stripe-logo.svg" alt="Stripe Logo" class="logo">
        <div class="invoice-details">
            <h1>Invoice</h1>
            <p><strong>Invoice #:</strong> {{ $payment_id }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
        </div>
    </header>

    <section class="customer-info">
        <h2>Player Details</h2>
        <p><strong>Name:</strong> {{ $player->name }}</p>
        <p><strong>Email:</strong> {{ $player->email }}</p>
    </section>

    <section class="billing-info">
        <h2>Coach Details</h2>
        <p><strong>Name:</strong> {{ $coach->name }}</p>
        <p><strong>Email:</strong> {{ $coach->email }}</p>
    </section>

    <section class="items">
        <h2>Payment Information</h2>
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
                    <td>{{ number_format($amount, 2) }} PKR</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="total-label">Total Paid</td>
                    <td class="total-amount">{{ number_format($amount, 2) }} PKR</td>
                </tr>
            </tfoot>
        </table>
    </section>

    <footer class="invoice-footer">
        <p>Thank you for choosing our platform!</p>
    </footer>
</div>

</body>
</html>

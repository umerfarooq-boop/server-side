<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Dear {{ $playerName }},</h1>
        <p>Your booking request has been received. However, before we can proceed, we kindly request you to complete your payment.</p>
        <p>Please ensure your payment is completed at your earliest convenience to confirm your booking.</p>
        <p>Thank you for your cooperation.</p>
        <p>Sincerely,<br>The Team</p>
    </div>
</body>
</html>

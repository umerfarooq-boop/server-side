<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Son Portal Login Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #4caf50;
            color: #ffffff;
            text-align: center;
            padding: 15px;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
        }
        .content p {
            margin: 10px 0;
        }
        .footer {
            background-color: #f4f4f9;
            text-align: center;
            padding: 15px;
            color: #666666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Portal Login Details</h2>
        </div>
        <div class="content">
            <p><strong>Your Son's Name:</strong> {{ $player->player_name }}</p>
            <p><strong>Your Password:</strong> {{ $plainPassword }}</p>
            <p><strong>Your Email:</strong> {{ $user->email }}</p>
            <p>These are the login credentials for your son's portal application. Please keep them secure.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Coach Selector. All rights reserved.</p>
        </div>
    </div>    
</body>
</html>

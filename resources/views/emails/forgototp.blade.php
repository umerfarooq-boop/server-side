<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #e0e0e0;
        }
        h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 20px;
        }
        .otp {
            display: inline-block;
            padding: 10px 20px;
            font-size: 30px;
            font-weight: bold;
            color: #ffffff;
            background-color: #4CAF50;
            border-radius: 4px;
            letter-spacing: 5px;
        }
        p {
            font-size: 16px;
            color: #666666;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #999999;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>ForGot OTP Verification</h1>
        <p>To continue, use the following OTP code:</p>
        <div class="otp">{{ $forgot_otp }}</div>
        <p>Please verify the code within one minute.</p>
        <p>If you didn't request this, please ignore this email.</p>
        <div class="footer">© 2024 Coach Selector. All rights reserved.</div>
    </div>
</body>
</html>

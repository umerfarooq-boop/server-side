<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da; /* Subtle alert background */
            color: #721c24; /* Dark red text for contrast */
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 2px solid #dc3545;
        }
        h2 {
            color: #ffffff;
            background-color: #dc3545;
            padding: 20px;
            margin: 0;
            text-align: center;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        p {
            padding: 15px 20px;
            margin: 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #c82333;
            color: white;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1c4c4;
        }
        .button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 12px 20px;
            background-color: #dc3545;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .button:hover {
            background-color: #c82333;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }
        .footer {
            padding: 15px 20px;
            font-size: 14px;
            color: #555;
            text-align: center;
            background-color: #f8d7da;
            border-top: 1px solid #dc3545;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Emergency Notification</h2>
        
        <p>Dear <strong>{{ $parent->name }}</strong>,</p>

        <p>We regret to inform you that an emergency has been reported for your child, <strong>{{ $player->name }}</strong>. Details of the incident are provided below:</p>

        <table>
            <tr>
                <th>Detail</th>
                <th>Description</th>
            </tr>
            <tr>
                <td><strong>Emergency Type</strong></td>
                <td>{{ $emergency->emergencyType }}</td>
            </tr>
            <tr>
                <td><strong>Sub-Type</strong></td>
                <td>{{ $emergency->subemergencyType ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Description</strong></td>
                <td>{{ $emergency->description }}</td>
            </tr>
        </table>

        <p>Please take appropriate action or contact our support team for further assistance. Your prompt attention to this matter is highly appreciated.</p>

        <a href="mailto:support@example.com" class="button">Contact Support</a>

        <div class="footer">
            <p>Stay safe,</p>
            <p><strong>Emergency Response Team</strong></p>
        </div>
    </div>
</body>
</html>

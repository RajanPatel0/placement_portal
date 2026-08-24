<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Placement Officer Account Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
            background: #f9f9f9;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .details {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Placement Officer Account Created</h1>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $name }}</strong>,</p>

            <p>Your placement officer account has been successfully created.</p>

            <div class="details">
                <h3>Account Details:</h3>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Designation:</strong> Placement Officer</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="https://test.ptu.ac.in/login" class="button">Login to Your Account</a>
            </div>

            <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <h4 style="margin-top: 0; color: #856404;">Security Notice:</h4>
                <ul style="margin-bottom: 0;">
                    <li>Please login and change your password immediately</li>
                    <li>Do not share your credentials with anyone</li>
                    <li>Keep your account information secure</li>
                </ul>
            </div>

            <p>If you have any questions, please contact the system administrator.</p>
        </div>

        <div class="footer">
            <p>Thanks,<br>IKGPTU Placement Cell</p>
        </div>
    </div>
</body>

</html>

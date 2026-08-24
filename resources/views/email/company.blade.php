<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        
        .email-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #71c842;
        }
        
      
        
        .login-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
            border-left: 4px solid #69cf32;
        }
        
        .credential {
            margin: 0px 0;
            padding: 4px;
        }
        
        .platform-link {
            display: block;
            text-align: center;
            background: #74d514;
            color: white;
            padding: 12px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        
        .security-note {
            background: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #ffeaa7;
            font-size: 14px;
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Welcome {{ $name }}!</h1>
        </div>

        <p>Your company account has been created successfully.</p>

        <div class="login-box">
            <h3>Login Details:</h3>
            <div class="credential">
                <strong>Email:</strong> {{ $email }}
            </div>
            <div class="credential">
                <strong>Password:</strong> {{ $password }}
            </div>
        </div>

        <a href="https://test.ptu.ac.in/" class="platform-link">
            Access Platform
        </a>

        <div class="security-note">
            <strong>Important:</strong> Please login and change your password as soon as possible.
        </div>

        <div class="footer">
            <p>Thank you,<br><strong>IKGPTU T&P Team</strong></p>
            <p style="margin-top: 15px; font-size: 12px;">
                This is an automated email. Please do not reply.
            </p>
        </div>
    </div>
</body>
</html>
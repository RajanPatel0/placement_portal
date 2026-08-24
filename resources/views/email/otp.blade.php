<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sub }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 5px;
            margin: 0 auto;
        }

        h1 {
            color: #5c67f2;
            font-size: 24px;
        }

        p {
            font-size: 16px;
        }

        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
            text-align: left;
            /* Changed from center to left */
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>{{ $sub }}</h1>

        <p>Hello, Dear</p>
        <p>{{ $msg }}</p>
        <p>Your OTP is: <strong>{{ $otp }}</strong></p>

        <p>If you did not request this, please ignore this email.</p>

        <p>Thank you for joining the IKGPTU family!</p>

        <div class="footer">
            <p>Best regards,</p>
           <p>The IKGPTU T&P Team</p>

        </div>
    </div>

</body>

</html>

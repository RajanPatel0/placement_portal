<!DOCTYPE html>
<html>

<head>
    <title>Password Reset OTP</title>
</head>

<body>
    <h2>Password Reset Request</h2>
    <p>Hello {{ $name }},</p>
    <p>You have requested to reset your password for IKGPTU T&P Portal. Use the OTP below to proceed:</p>

    <div
        style="background: #f4f4f4; padding: 10px; margin: 15px 0; font-size: 24px; font-weight: bold; text-align: center;">
        {{ $otp }}
    </div>

    <p>This OTP will expire in 10 minutes.</p>
    <p>If you didn't request this, please ignore this email.</p>

    <br>
    <p>Thank you,<br>IKGPTU T&P Team</p>
</body>

</html>
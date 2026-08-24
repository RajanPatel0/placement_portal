<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Placement Drive Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .info-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            color: #4a5568;
            min-width: 120px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎯 Placement Drive Notification</h1>
        <p>Exciting Opportunity Awaits!</p>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $studentName }}</strong>,</p>

        <p>We are pleased to inform you about an upcoming placement drive that matches your profile. Here are the
            details:</p>

        <div class="details">
            <div class="company-name">{{ $companyName }}</div>

            <div class="info-item">
                <span class="info-label">Job Role:</span>
                <span>{{ $jobRole }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Drive Date:</span>
                <span>{{ \Carbon\Carbon::parse($driveDate)->format('F j, Y') }}</span>
            </div>

            @if($description)
            <div class="info-item">
                <span class="info-label">Description:</span>
                <span>{{ $description }}</span>
            </div>
            @endif
        </div>

        <p>This is a great opportunity to kickstart your career with a reputed company. Make sure to:</p>
        <ul>
            <li>Update your resume</li>
            <li>Prepare for the interview process</li>
            <li>Research about the company</li>
            <li>Be on time for the drive</li>
        </ul>

        <p>For more details and to confirm your participation, please check the college placement portal or contact the
            placement cell.</p>

        <div style="text-align: center;">
            <a href="https://snow-quail-310589.hostingersite.com" class="button">View Details on Portal</a>
        </div>
    </div>

    <div class="footer">
        <p>Best regards,<br>
            IKGPTU<br>
            T&P CELL
        </p>

        <p style="margin-top: 10px; font-size: 12px;">
            This is an automated notification. Please do not reply to this email.
        </p>
    </div>
</body>

</html>
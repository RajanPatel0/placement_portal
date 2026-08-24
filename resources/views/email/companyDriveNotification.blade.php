<!DOCTYPE html>
<html>

<head>
    <title>Drive Scheduled Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }

        .drive-details {
            background-color: white;
            border-left: 4px solid #4a90e2;
            padding: 15px;
            margin: 20px 0;
            border-radius: 3px;
        }

        .detail-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Drive Scheduled Successfully</h1>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $contactPerson }}</strong>,</p>

        <p>Your drive has been successfully scheduled with the following details:</p>

        <div class="drive-details">
            <div class="detail-item">
                <span class="label">Company:</span>
                <span>{{ $companyName }}</span>
            </div>

            @if ($jobTitle)
                <div class="detail-item">
                    <span class="label">Job Title:</span>
                    <span>{{ $jobTitle }}</span>
                </div>
            @endif

            @if ($driveDate)
                <div class="detail-item">
                    <span class="label">Drive Date:</span>
                    <span>{{ $driveDate }}</span>
                </div>
            @endif

            <div class="detail-item">
                <span class="label">Contact Email:</span>
                <span>{{ $contactEmail }}</span>
            </div>
        </div>

        <p>The drive details have been recorded in our system. You will be notified of any updates or student
            registrations.</p>

        <p>If you need to make any changes to this drive, please contact the placement coordinator.</p>

        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>© {{ date('Y') }} IKGPTU Placement Cell. All rights reserved.</p>
        </div>
    </div>
</body>

</html>

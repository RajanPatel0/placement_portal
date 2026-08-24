<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shortlisted by {{ $companyName }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            background: linear-gradient(135deg, #dbdb34, #b96f29);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .content {
            padding: 25px;
        }
        
        .highlight {
            background: #f0f7f0;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #afaa4c;
        }
        
        .contact-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            padding: 20px;
            border-top: 1px solid #eee;
        }
        
        .company-name {
            font-weight: bold;
            color: #2E7D32;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Congratulations {{ $studentName }}!</h1>
        </div>

        <div class="content">
            <p>You have been shortlisted by <strong>{{ $companyName }}</strong> for:</p>
            
            <div class="highlight">
                <strong>{{ $driveName }}</strong>
            </div>
            
            <p><strong>Shortlisted Date:</strong> {{ $shortlistedDate }}</p>
            
            <p>{{ $nextSteps }}</p>
            
             <div class="contact-box">
                <p><strong>Contact Email:</strong><br>
                <a href="mailto:{{ $contactEmail }}" style="color: #2196F3; text-decoration: none; font-weight: 600;">{{ $contactEmail }}</a></p>
               
            </div>
            
            <p>Best regards,<br>
                <strong>{{ $companyName }} HR Team</strong>
            </p>
        </div>

        <div class="footer">
            <div class="company-name">{{ $companyName }}</div>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
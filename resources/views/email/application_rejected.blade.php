{{-- resources/views/email/application_rejected.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Application Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        .status-badge { 
            background: #f8d7da; 
            color: #721c24; 
            padding: 10px 20px; 
            border-radius: 5px; 
            font-weight: bold; 
            margin: 15px 0; 
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $companyName }} - Application Update</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $studentName }},</p>
            
            <div class="status-badge">
                Application Status Update
            </div>
            
            <p>Thank you for participating in the selection process for the position of <strong>{{ $jobRole }}</strong> at <strong>{{ $companyName }}</strong>.</p>
            
            <p>{{ nl2br($description) }}</p>
            
            @if($driveDate)
                <p><strong>Drive Date:</strong> {{ $driveDate }}</p>
            @endif
            
            <p>We encourage you to continue applying to other placement opportunities and wish you the best in your career journey.</p>
            
            <p>Best regards,<br>
            <strong>{{ $companyName }}</strong> HR Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
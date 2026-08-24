<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Selected by {{ $companyName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: white;
            border-radius: 50% 50% 0 0;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .header h2 {
            font-size: 22px;
            font-weight: 500;
            opacity: 0.95;
        }
        
        .celebration-icon {
            font-size: 60px;
            margin-bottom: 15px;
            display: block;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .content {
            padding: 40px 35px;
        }
        
        .intro-text {
            font-size: 18px;
            margin-bottom: 25px;
            text-align: center;
            color: #444;
        }
        
        .offer-details {
            background: linear-gradient(to right, #e8f5e9, #c8e6c9);
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
            border-left: 5px solid #4CAF50;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .offer-details h3 {
            color: #2E7D32;
            margin-bottom: 18px;
            font-size: 22px;
            border-bottom: 2px solid #a5d6a7;
            padding-bottom: 8px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            align-items: center;
        }
        
        .detail-icon {
            width: 24px;
            margin-right: 12px;
            color: #4CAF50;
            font-size: 18px;
        }
        
        .detail-label {
            font-weight: 600;
            min-width: 140px;
            color: #2E7D32;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .next-steps {
            margin: 30px 0;
        }
        
        .next-steps h3 {
            color: #2E7D32;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .steps-list {
            list-style-type: none;
            padding-left: 0;
        }
        
        .steps-list li {
            padding: 12px 15px;
            margin-bottom: 10px;
            background: #f1f8e9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: transform 0.2s;
        }
        
        .steps-list li:hover {
            transform: translateX(5px);
            background: #e8f5e8;
        }
        
        .steps-list li::before {
            content: '✓';
            color: #4CAF50;
            font-weight: bold;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 25px 0;
            border: 1px solid #e0e0e0;
        }
        
        .contact-info strong {
            color: #2E7D32;
            font-size: 18px;
        }
        
        .contact-email {
            color: #4CAF50;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            margin-top: 8px;
        }
        
        .welcome-message {
            text-align: center;
            font-size: 20px;
            margin: 25px 0;
            color: #2E7D32;
            font-weight: 600;
        }
        
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 25px;
            font-size: 14px;
        }
        
        .company-logo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        
        @media (max-width: 600px) {
            .content {
                padding: 25px 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 26px;
            }
            
            .header h2 {
                font-size: 18px;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <span class="celebration-icon">🎉</span>
            <h1>Congratulations {{ $studentName }}!</h1>
            <h2>You've Been Selected!</h2>
        </div>

        <div class="content">
            <p class="intro-text">We are thrilled to inform you that you have been <strong>finally selected</strong> by <strong>{{ $companyName }}</strong>!</p>

            <div class="offer-details">
                <h3>Offer Details</h3>
                <div class="detail-row">
                    <span class="detail-icon">💼</span>
                    <span class="detail-label">Position:</span>
                    <span class="detail-value">{{ $jobTitle }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-icon">💰</span>
                    <span class="detail-label">Package:</span>
                    <span class="detail-value">{{ $package }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-icon">🏢</span>
                    <span class="detail-label">Placement Drive:</span>
                    <span class="detail-value">{{ $driveName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-icon">📅</span>
                    <span class="detail-label">Selected Date:</span>
                    <span class="detail-value">{{ $selectedDate }}</span>
                </div>
            </div>

            <div class="next-steps">
                <h3>Next Steps</h3>
                <p>Our HR team will contact you shortly with details regarding:</p>
                <ul class="steps-list">
                    <li>Offer letter and documentation</li>
                    <li>Joining formalities</li>
                    <li>Onboarding process</li>
                    <li>Any other required information</li>
                </ul>
            </div>

            <div class="contact-info">
                <strong>Contact Email:</strong><br>
                <a href="mailto:{{ $contactEmail }}" class="contact-email">{{ $contactEmail }}</a>
            </div>

            <div class="welcome-message">
                Welcome to the <strong>{{ $companyName }}</strong> family!
            </div>

            <div class="signature">
                <p>Best regards,<br>
                    <strong>HR Team - {{ $companyName }}</strong>
                </p>
            </div>
        </div>

        <div class="footer">
            <div class="company-logo">{{ $companyName }}</div>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
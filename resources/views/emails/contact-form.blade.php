<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Inquiry</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #4b5563;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #1f2937;
            font-size: 16px;
            margin-top: 5px;
        }
        .message-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 New Contact Inquiry</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Addis Mark Real Estate</p>
        </div>
        
        <div class="content">
            <div class="info-box">
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $data['name'] }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $data['email'] }}</div>
                </div>
                
                @if(isset($data['phone']))
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $data['phone'] }}</div>
                </div>
                @endif
                
                <div class="info-item">
                    <div class="info-label">Subject</div>
                    <div class="info-value">{{ $data['subject'] }}</div>
                </div>
            </div>
            
            <div class="message-box">
                <div class="info-label" style="margin-bottom: 10px;">Message</div>
                <div style="white-space: pre-wrap;">{{ $data['message'] }}</div>
            </div>
            
            <div style="text-align: center;">
                <a href="mailto:{{ $data['email'] }}" class="button">Reply to {{ $data['name'] }}</a>
            </div>
        </div>
        
        <div class="footer">
            <p>This email was sent from the Addis Mark Real Estate contact form.</p>
            <p>📍 Bole Road, Addis Ababa, Ethiopia</p>
            <p>📞 +251 11 234 5678</p>
        </div>
    </div>
</body>
</html>
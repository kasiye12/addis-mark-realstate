<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Inquiry</title>
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
        .property-preview {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            gap: 20px;
        }
        .property-info {
            flex: 1;
        }
        .property-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .property-price {
            color: #2563eb;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .property-link {
            display: inline-block;
            color: #2563eb;
            text-decoration: none;
            margin-top: 10px;
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
            <h1>🔑 Property Inquiry</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">New Client Interest</p>
        </div>
        
        <div class="content">
            <div class="property-preview">
                <div class="property-info">
                    <div class="property-title">{{ $property->title }}</div>
                    <div class="property-price">{{ $property->formatted_price }}</div>
                    <div style="color: #6b7280; font-size: 14px;">
                        📍 {{ $property->location->full_address }}
                    </div>
                    <a href="{{ route('properties.show', $property->slug) }}" class="property-link">
                        View Property Details →
                    </a>
                </div>
            </div>
            
            <div class="info-box">
                <div class="info-item">
                    <div class="info-label">Client Name</div>
                    <div class="info-value">{{ $data['name'] }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $data['email'] }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $data['phone'] }}</div>
                </div>
                
                @if(isset($data['message']))
                <div class="info-item">
                    <div class="info-label">Additional Message</div>
                    <div class="info-value" style="white-space: pre-wrap;">{{ $data['message'] }}</div>
                </div>
                @endif
            </div>
            
            <div style="text-align: center;">
                <a href="mailto:{{ $data['email'] }}" class="button">Reply to Client</a>
                <a href="tel:{{ $data['phone'] }}" class="button" style="background: #10b981; margin-left: 10px;">
                    Call Client
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>This inquiry was sent from the Addis Mark Real Estate website.</p>
            <p>🏢 Addis Mark Real Estate - Premium Properties in Ethiopia</p>
        </div>
    </div>
</body>
</html>
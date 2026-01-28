<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting ServeDavao</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header { 
            background: linear-gradient(135deg, #10b981, #059669);
            color: white; 
            padding: 30px 20px; 
            text-align: center; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 24px;
            font-weight: 600;
        }
        .content { 
            padding: 30px; 
        }
        .message-box {
            background: #f8fafc;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .footer { 
            text-align: center; 
            padding: 20px; 
            background: #f8fafc;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            background: #10b981;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👋 Hello {{ $firstName }}!</h1>
        </div>
        
        <div class="content">
            <p>Thank you for contacting <strong>ServeDavao</strong>! We have received your message and will get back to you within 24 hours.</p>
            
            <p>Here is a summary of your inquiry:</p>
            
            <div class="message-box">
                <div style="font-size: 12px; color: #10b981; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Subject</div>
                <div style="font-weight: 600; margin-bottom: 15px;">{{ $subject }}</div>
                
                <div style="font-size: 12px; color: #10b981; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Your Message</div>
                <div style="color: #4b5563;">{{ $contactMessage }}</div>
            </div>

            <p>If you have any urgent questions, please don't hesitate to call us at <strong>+63 82 123 4567</strong>.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Visit Our Website</a>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ServeDavao. All rights reserved.</p>
            <p>Davao City, Philippines</p>
        </div>
    </div>
</body>
</html>

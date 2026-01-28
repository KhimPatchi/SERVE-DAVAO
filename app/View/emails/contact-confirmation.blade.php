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
            padding: 40px 20px; 
            text-align: center; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 28px;
            font-weight: 600;
        }
        .content { 
            padding: 40px; 
        }
        .greeting {
            font-size: 18px;
            color: #374151;
            margin-bottom: 25px;
        }
        .summary-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #10b981;
        }
        .summary-item {
            margin-bottom: 12px;
            display: flex;
        }
        .summary-label {
            font-weight: 600;
            color: #10b981;
            min-width: 80px;
        }
        .contact-info {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .footer { 
            text-align: center; 
            padding: 30px; 
            background: #f8fafc;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Contacting ServeDavao!</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $firstName }}</strong>,
            </div>

            <p>Thank you for reaching out to ServeDavao! We have successfully received your message and our team will review it shortly.</p>

            <div class="summary-box">
                <h3 style="margin-top: 0; color: #10b981;">Your Inquiry Summary</h3>
                
                <div class="summary-item">
                    <span class="summary-label">Subject:</span>
                    <span style="flex: 1;">{{ $subjectLabel }}</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Message:</span>
                    <span style="flex: 1; white-space: pre-wrap;">{{ $message }}</span>
                </div>
            </div>

            <div class="contact-info">
                <h4 style="margin-top: 0; color: #d97706;">Need Immediate Assistance?</h4>
                <p>If your matter is urgent, please feel free to contact us directly:</p>
                <p>📞 <strong>Phone:</strong> +63 82 123 4567</p>
                <p>📧 <strong>Email:</strong> khimdavin24@gmail.com</p>
            </div>

            <p>We typically respond to all inquiries within <strong>24 hours</strong>. We appreciate your patience and look forward to assisting you!</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" class="button">Visit Our Website</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Best regards,</strong><br>The ServeDavao Team</p>
            <p>&copy; {{ date('Y') }} ServeDavao. Empowering communities through service.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .header img {
            max-height: 60px;
            height: auto;
        }
        .nav {
            background: #f8fafc;
            padding: 12px 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .nav a {
            color: #475569;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
            transition: color 0.3s;
        }
        .nav a:hover {
            color: #2563eb;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #475569;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .highlight-box {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .highlight-box h3 {
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .highlight-box p {
            margin: 0;
            color: #475569;
        }
        .info-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info-item {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #1e293b;
        }
        .info-value {
            color: #475569;
        }
        .footer {
            background: #1e293b;
            color: #cbd5e1;
            padding: 30px 20px;
        }
        .footer-content {
            max-width: 560px;
            margin: 0 auto;
        }
        .footer-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer-logo img {
            max-width: 150px;
            height: auto;
        }
        .footer-links {
            text-align: center;
            margin: 20px 0;
        }
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            margin: 0 10px;
            font-size: 13px;
        }
        .footer-links a:hover {
            color: #60a5fa;
        }
        .footer-info {
            text-align: center;
            font-size: 13px;
            color: #64748b;
            margin-top: 20px;
        }
        .copyright {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header">
            <a href="https://codeforgesystems.co.uk">
                <img src="https://codeforgesystems.co.uk/assets/logo_colour.png" alt="CodeForge Systems">
            </a>
        </div>

        <!-- Navigation -->
        <div class="nav">
            <a href="https://codeforgesystems.co.uk/">Home</a>
            <a href="https://codeforgesystems.co.uk/services">Services</a>
            <a href="https://codeforgesystems.co.uk/portfolio">Portfolio</a>
            <a href="https://codeforgesystems.co.uk/configurator">Quote Builder</a>
            <a href="https://codeforgesystems.co.uk/contact">Contact</a>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h1 class="greeting">Hi {{ $submission->name }},</h1>

            <div class="message">
                <p>Thank you for reaching out to CodeForge Systems. We have received your message and appreciate you taking the time to contact us.</p>
            </div>

            <div class="highlight-box">
                <h3>What happens next?</h3>
                <p>Our team will review your inquiry and get back to you within <strong>48 hours</strong>. We're committed to providing you with the information and support you need.</p>
            </div>

            <div class="info-box">
                <div class="info-item">
                    <span class="info-label">Submission Reference:</span>
                    <span class="info-value">#{{ $submission->id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Submitted on:</span>
                    <span class="info-value">{{ $submission->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="message">
                <p>In the meantime, feel free to explore our services or check out our portfolio to see examples of our work.</p>
                <p>If you have any urgent questions, please don't hesitate to reach out to us directly.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="https://codeforgesystems.co.uk/assets/logo_white.png" alt="CodeForge Systems">
                </div>

                <div class="footer-info">
                    Professional software engineering services for businesses across the UK
                </div>

                <div class="footer-links">
                    <a href="https://codeforgesystems.co.uk/services?tab=contractor">Contract Development</a>
                    <a href="https://codeforgesystems.co.uk/services">Web Applications</a>
                    <a href="https://codeforgesystems.co.uk/services">AI Integration</a>
                    <a href="https://codeforgesystems.co.uk/services">Website Packages</a>
                </div>

                <div class="footer-links">
                    <a href="https://codeforgesystems.co.uk/privacy">Privacy Policy</a>
                    <a href="https://codeforgesystems.co.uk/terms">Terms of Service</a>
                    <a href="https://codeforgesystems.co.uk/cookies">Cookie Policy</a>
                </div>

                <div class="copyright">
                    &copy; {{ date('Y') }} CodeForge Systems. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>

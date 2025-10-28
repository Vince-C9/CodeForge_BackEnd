<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
            background: #1E40AF;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-label {
            font-weight: 600;
            color: #1E40AF;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            background: #f8fafc;
            padding: 12px 15px;
            border-radius: 6px;
            border-left: 4px solid #3B82F6;
            margin-top: 5px;
        }
        .message-box {
            background: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #3B82F6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .metadata {
            background: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            font-size: 13px;
            color: #64748b;
            margin-top: 25px;
        }
        .metadata-item {
            margin-bottom: 8px;
        }
        .metadata-item:last-child {
            margin-bottom: 0;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
        </div>

        <div class="content">
            <div class="info-section">
                <div class="info-label">Contact Reason</div>
                <div class="info-value">
                    <span class="badge">{{ $contactReason }}</span>
                </div>
            </div>

            @if($service)
            <div class="info-section">
                <div class="info-label">Service Interested In</div>
                <div class="info-value">{{ $service }}</div>
            </div>
            @endif

            <div class="info-section">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $submission->name }}</div>
            </div>

            <div class="info-section">
                <div class="info-label">Email</div>
                <div class="info-value">
                    <a href="mailto:{{ $submission->email }}" style="color: #3B82F6; text-decoration: none;">
                        {{ $submission->email }}
                    </a>
                </div>
            </div>

            @if($submission->phone)
            <div class="info-section">
                <div class="info-label">Phone</div>
                <div class="info-value">
                    <a href="tel:{{ $submission->phone }}" style="color: #3B82F6; text-decoration: none;">
                        {{ $submission->phone }}
                    </a>
                </div>
            </div>
            @endif

            <div class="info-section">
                <div class="info-label">Message</div>
                <div class="message-box">{{ $submission->message }}</div>
            </div>

            <div class="metadata">
                <div class="metadata-item">
                    <strong>Submission ID:</strong> #{{ $submission->id }}
                </div>
                <div class="metadata-item">
                    <strong>Date & Time:</strong> {{ $submission->created_at->format('d M Y, H:i:s') }}
                </div>
                @if($submission->recaptcha_score)
                <div class="metadata-item">
                    <strong>reCAPTCHA Score:</strong> {{ $submission->recaptcha_score }}/1.0
                    @if($submission->recaptcha_score >= 0.7)
                        <span style="color: #059669;">✓ Verified</span>
                    @elseif($submission->recaptcha_score >= 0.5)
                        <span style="color: #d97706;">⚠ Medium</span>
                    @else
                        <span style="color: #dc2626;">⚠ Low</span>
                    @endif
                </div>
                @endif
                @if($submission->ip_address)
                <div class="metadata-item">
                    <strong>IP Address:</strong> {{ $submission->sanitized_ip ?? $submission->ip_address }}
                </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from your CodeForge Systems website contact form.</p>
            <p>Please respond to this inquiry at your earliest convenience.</p>
        </div>
    </div>
</body>
</html>

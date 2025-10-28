<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Website Quote Request</title>
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
            background: #059669;
            color: #000000;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .price-banner {
            background: #d1fae5;
            color: #065f46;
            padding: 15px 20px;
            text-align: center;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 30px 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: 600;
            color: #059669;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            background: #f8fafc;
            padding: 12px 15px;
            border-radius: 6px;
            border-left: 4px solid #10b981;
            margin-top: 5px;
        }
        .color-swatch {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
            margin-right: 10px;
            vertical-align: middle;
        }
        .tag {
            display: inline-block;
            padding: 4px 10px;
            margin: 3px;
            background: #e0f2fe;
            color: #075985;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .price-breakdown {
            background: #f0fdf4;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #d1fae5;
        }
        .price-row:last-child {
            border-bottom: none;
        }
        .price-row.total {
            font-size: 18px;
            font-weight: 700;
            color: #065f46;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #10b981;
        }
        .feature-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f8fafc;
            border-radius: 4px;
            margin-bottom: 8px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 New Website Quote Request</h1>
        </div>

        <div class="price-banner">
            Estimated Total: £{{ number_format($submission->total_price, 2) }}
        </div>

        <div class="content">
            <h2 class="section-title">👤 Client Details</h2>

            <div class="info-section">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $submission->name }}</div>
            </div>

            <div class="info-section">
                <div class="info-label">Email</div>
                <div class="info-value">
                    <a href="mailto:{{ $submission->email }}" style="color: #10b981; text-decoration: none;">
                        {{ $submission->email }}
                    </a>
                </div>
            </div>

            @if($submission->phone)
            <div class="info-section">
                <div class="info-label">Phone</div>
                <div class="info-value">
                    <a href="tel:{{ $submission->phone }}" style="color: #10b981; text-decoration: none;">
                        {{ $submission->phone }}
                    </a>
                </div>
            </div>
            @endif

            @if($submission->message)
            <div class="info-section">
                <div class="info-label">Additional Notes</div>
                <div class="info-value" style="white-space: pre-wrap;">{{ $submission->message }}</div>
            </div>
            @endif

            <h2 class="section-title">⚙️ Website Configuration</h2>

            <div class="info-section">
                <div class="info-label">Website Type</div>
                <div class="info-value">{{ $pageType }}</div>
            </div>

            <div class="info-section">
                <div class="info-label">Color Scheme</div>
                <div class="info-value">
                    <div style="margin-bottom: 10px;">
                        <span class="color-swatch" style="background-color: {{ $configuration['colors']['primary'] }};"></span>
                        <strong>Primary:</strong> {{ $configuration['colors']['primary'] }}
                    </div>
                    <div>
                        <span class="color-swatch" style="background-color: {{ $configuration['colors']['secondary'] }};"></span>
                        <strong>Secondary:</strong> {{ $configuration['colors']['secondary'] }}
                    </div>
                </div>
            </div>

            @if(isset($configuration['logo_path']))
            <div class="info-section">
                <div class="info-label">Logo</div>
                <div class="info-value">✓ Logo file uploaded</div>
            </div>
            @endif

            <div class="info-section">
                <div class="info-label">Default Sections Included</div>
                <div class="info-value">
                    @foreach($configuration['sections'] as $section)
                        <span class="tag">{{ ucwords(str_replace('-', ' ', $section)) }}</span>
                    @endforeach
                </div>
            </div>

            @if(count($configuration['additionalPages'] ?? []) > 0)
            <div class="info-section">
                <div class="info-label">Additional Pages (£50 each)</div>
                <div class="info-value">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($configuration['additionalPages'] as $page)
                            <li>{{ $page['name'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(count($features) > 0)
            <div class="info-section">
                <div class="info-label">Premium Features</div>
                <div class="info-value">
                    @foreach($features as $feature)
                        <div class="feature-item">
                            <span>{{ $feature['name'] }}</span>
                            <span style="font-weight: 600; color: #059669;">+£{{ number_format($feature['price'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <h2 class="section-title">💵 Price Breakdown</h2>

            <div class="price-breakdown">
                <div class="price-row">
                    <span>Base Website</span>
                    <span style="font-weight: 600;">£{{ number_format($basePrice, 2) }}</span>
                </div>

                @if($additionalPagesCount > 0)
                <div class="price-row">
                    <span>Additional Pages ({{ $additionalPagesCount }} × £50)</span>
                    <span style="font-weight: 600;">£{{ number_format($additionalPagesPrice, 2) }}</span>
                </div>
                @endif

                @if($featuresTotal > 0)
                <div class="price-row">
                    <span>Premium Features</span>
                    <span style="font-weight: 600;">£{{ number_format($featuresTotal, 2) }}</span>
                </div>
                @endif

                <div class="price-row total">
                    <span>Estimated Total</span>
                    <span>£{{ number_format($submission->total_price, 2) }}</span>
                </div>

                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #d1fae5; font-size: 12px; color: #065f46;">
                    + £120/year for hosting & domain
                </div>
            </div>

            <div class="metadata">
                <div class="metadata-item">
                    <strong>Submission ID:</strong> #{{ $submission->id }}
                </div>
                <div class="metadata-item">
                    <strong>Date & Time:</strong> {{ $submission->created_at->format('d M Y, H:i:s') }}
                </div>
                @if($submission->ip_address)
                <div class="metadata-item">
                    <strong>IP Address:</strong> {{ $submission->sanitized_ip ?? $submission->ip_address }}
                </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p><strong>Action Required:</strong> Please review this quote request and respond within 48 hours.</p>
            <p>This is an automated notification from your CodeForge Systems website configurator.</p>
        </div>
    </div>
</body>
</html>

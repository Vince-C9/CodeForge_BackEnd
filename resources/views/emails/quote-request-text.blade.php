NEW WEBSITE QUOTE REQUEST
================================

ESTIMATED TOTAL: £{{ number_format($submission->total_price, 2) }}


CLIENT DETAILS
--------------
Name: {{ $submission->name }}
Email: {{ $submission->email }}
@if($submission->phone)
Phone: {{ $submission->phone }}
@endif

@if($submission->message)
Additional Notes:
{{ $submission->message }}
@endif


WEBSITE CONFIGURATION
---------------------
Website Type: {{ $pageType }}

Color Scheme:
- Primary: {{ $configuration['colors']['primary'] }}
- Secondary: {{ $configuration['colors']['secondary'] }}

@if(isset($configuration['logo_path']))
Logo: Uploaded
@endif

Default Sections:
@foreach($configuration['sections'] as $section)
- {{ ucwords(str_replace('-', ' ', $section)) }}
@endforeach

@if(count($configuration['additionalPages'] ?? []) > 0)
Additional Pages (£50 each):
@foreach($configuration['additionalPages'] as $page)
- {{ $page['name'] }}
@endforeach
@endif

@if(count($features) > 0)
Premium Features:
@foreach($features as $feature)
- {{ $feature['name'] }}: +£{{ number_format($feature['price'], 2) }}
@endforeach
@endif


PRICE BREAKDOWN
---------------
Base Website: £{{ number_format($basePrice, 2) }}
@if($additionalPagesCount > 0)
Additional Pages ({{ $additionalPagesCount }} × £50): £{{ number_format($additionalPagesPrice, 2) }}
@endif
@if($featuresTotal > 0)
Premium Features: £{{ number_format($featuresTotal, 2) }}
@endif
-----------------------------------
ESTIMATED TOTAL: £{{ number_format($submission->total_price, 2) }}
+ £120/year for hosting & domain


SUBMISSION DETAILS
------------------
Submission ID: #{{ $submission->id }}
Date & Time: {{ $submission->created_at->format('d M Y, H:i:s') }}
@if($submission->ip_address)
IP Address: {{ $submission->sanitized_ip ?? $submission->ip_address }}
@endif

---
ACTION REQUIRED: Please review this quote request and respond within 24 hours.
This is an automated notification from your CodeForge Systems website configurator.

NEW CONTACT FORM SUBMISSION
================================

Contact Reason: {{ $contactReason }}
@if($service)
Service: {{ $service }}
@endif

CONTACT DETAILS
---------------
Name: {{ $submission->name }}
Email: {{ $submission->email }}
@if($submission->phone)
Phone: {{ $submission->phone }}
@endif

MESSAGE
-------
{{ $submission->message }}


SUBMISSION DETAILS
------------------
Submission ID: #{{ $submission->id }}
Date & Time: {{ $submission->created_at->format('d M Y, H:i:s') }}
@if($submission->recaptcha_score)
reCAPTCHA Score: {{ $submission->recaptcha_score }}/1.0
@endif
@if($submission->ip_address)
IP Address: {{ $submission->sanitized_ip ?? $submission->ip_address }}
@endif

---
This is an automated notification from your CodeForge Systems website contact form.
Please respond to this inquiry at your earliest convenience.

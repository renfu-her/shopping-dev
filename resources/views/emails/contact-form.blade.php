<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background: #fff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .field-value {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }
        .message-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Form Submission</h2>
        <p>You have received a new contact form submission from your website.</p>
    </div>

    <div class="content">
        <div class="field">
            <div class="field-label">Name:</div>
            <div class="field-value">{{ $contact->full_name }}</div>
        </div>

        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">{{ $contact->email }}</div>
        </div>

        @if($contact->phone)
        <div class="field">
            <div class="field-label">Phone:</div>
            <div class="field-value">{{ $contact->phone }}</div>
        </div>
        @endif

        <div class="field">
            <div class="field-label">Subject:</div>
            <div class="field-value">{{ $contact->subject_label }}</div>
        </div>

        <div class="field">
            <div class="field-label">Message:</div>
            <div class="message-content">{{ $contact->message }}</div>
        </div>

        @if($contact->newsletter_subscription)
        <div class="field">
            <div class="field-label">Newsletter Subscription:</div>
            <div class="field-value">Yes - User wants to subscribe to newsletter</div>
        </div>
        @endif

        <div class="field">
            <div class="field-label">Submitted:</div>
            <div class="field-value">{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>

        <div class="field">
            <div class="field-label">IP Address:</div>
            <div class="field-value">{{ $contact->ip_address ?? 'Not available' }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This email was sent from your website contact form.</p>
        <p>Contact ID: {{ $contact->id }}</p>
    </div>
</body>
</html>

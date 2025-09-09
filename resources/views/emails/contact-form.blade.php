<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="margin: 0 0 10px 0; color: #333;">New Contact Form Submission</h2>
        <p style="margin: 0; color: #666;">You have received a new contact form submission from your website.</p>
    </div>

    <div style="background: #fff; padding: 20px; border: 1px solid #dee2e6; border-radius: 8px;">
        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Name:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">{{ $contact->full_name }}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Email:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">{{ $contact->email }}</div>
        </div>

        @if($contact->phone)
        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Phone:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">{{ $contact->phone }}</div>
        </div>
        @endif

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Subject:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">{{ $contact->subject_label }}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Message:</div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #28a745; white-space: pre-wrap;">{{ $contact->message }}</div>
        </div>

        @if($contact->newsletter_subscription)
        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Newsletter Subscription:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">Yes - User wants to subscribe to newsletter</div>
        </div>
        @endif

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">Submitted:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">IP Address:</div>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff;">{{ $contact->ip_address ?? 'Not available' }}</div>
        </div>
    </div>

    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d;">
        <p style="margin: 0 0 5px 0;">This email was sent from your website contact form.</p>
        <p style="margin: 0;">Contact ID: {{ $contact->id }}</p>
    </div>
</body>
</html>

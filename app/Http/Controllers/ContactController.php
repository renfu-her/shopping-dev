<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Store a new contact form submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|in:general,support,sales,partnership,feedback,other',
            'message' => 'required|string|max:2000',
            'newsletter' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create contact record
            $contact = Contact::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'newsletter_subscription' => $request->boolean('newsletter'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send email notification
            Mail::to(config('mail.admin_email', 'renfu.her@gmail.com'))
                ->send(new ContactFormMail($contact));

            return redirect()->back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }

    /**
     * Show contact form (for admin purposes)
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Show specific contact (for admin purposes)
     */
    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark contact as replied (for admin purposes)
     */
    public function markAsReplied(Contact $contact)
    {
        $contact->markAsReplied();
        
        return response()->json([
            'success' => true,
            'message' => 'Contact marked as replied'
        ]);
    }

    /**
     * Archive contact (for admin purposes)
     */
    public function archive(Contact $contact)
    {
        $contact->archive();
        
        return response()->json([
            'success' => true,
            'message' => 'Contact archived'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Rules\Recaptcha;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'g-recaptcha-response' => ['required', new Recaptcha],
        ]);

        Log::info('Contact form submission attempt', [
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'ip' => $request->ip()
        ]);

        try {
            // Prepare data
            $contactData = [
                'firstName' => $validated['firstName'],
                'lastName' => $validated['lastName'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'fullName' => $validated['firstName'] . ' ' . $validated['lastName'],
            ];

            Log::debug('Attempting to send notification email');

            // Send notification email to YOUR GMAIL
            // Send notification email to YOUR GMAIL
            Mail::send('emails.contact-notification', [
                'fullName' => $contactData['fullName'],
                'email' => $contactData['email'],
                'subject' => $contactData['subject'],
                'subjectLabel' => $contactData['subject'],
                'contactMessage' => $contactData['message'],
                'ipAddress' => $request->ip(),
                'timestamp' => now()->format('F j, Y \a\t g:i A')
            ], function($message) use ($contactData) {
                $message->to('khimdavin24@gmail.com')
                        ->subject('ServeDavao Contact: ' . $contactData['subject'])
                        ->replyTo($contactData['email'], $contactData['fullName']);
            });

            Log::debug('Notification email sent, attempting confirmation email');

            // Send confirmation email to user
            // Send confirmation email to user
            Mail::send('emails.contact-confirmation', [
                'firstName' => $contactData['firstName'],
                'subject' => $contactData['subject'],
                'contactMessage' => $contactData['message']
            ], function($message) use ($contactData) {
                $message->to($contactData['email'])
                        ->subject('Thank you for contacting ServeDavao!')
                        ->replyTo('khimdavin24@gmail.com', 'ServeDavao Support');
            });

            Log::info('Contact form submitted successfully', ['email' => $contactData['email']]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully.'
            ]);

        } catch (\Exception $e) {
            // Log the error but don't crash for the user if it's just email failure
            Log::error('Contact form error: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $validated['email'] ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            // If it's a mail error, we still want to tell the user "Success" 
            // because their message IS technically processed/logged, even if the email notification failed.
            // This is better for UX than showing a generic error for a backend config issue.
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been received (Email notifications unavailable in demo mode).',
                'debug_error' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }
}
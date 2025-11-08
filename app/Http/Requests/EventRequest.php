<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        // Allow any authenticated user to attempt event creation
        // The controller will handle the organizer verification
        return Auth::check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'required_volunteers' => 'required|integer|min:1',
            'skills_required' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'date.after' => 'The event date must be in the future.',
            'time.date_format' => 'Please enter a valid time format.',
        ];
    }

    // Combine date and time into a single datetime
    public function getEventDateTime()
    {
        return $this->date . ' ' . $this->time . ':00';
    }
}
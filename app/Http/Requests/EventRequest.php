<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'date'                => 'required|date',
            'time'                => 'required|date_format:H:i',
            'end_time'            => 'required|date_format:H:i',
            'location'            => 'required|string|max:255',
            'latitude'            => 'nullable|numeric|between:-90,90',
            'longitude'           => 'nullable|numeric|between:-180,180',
            'target_radius'       => 'nullable|numeric|min:1|max:100',
            'required_volunteers' => 'required|integer|min:1',
            'skills_preferred'    => 'nullable|string|max:255',
            'event_image'         => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'time.date_format'     => 'Please enter a valid start time.',
            'end_time.date_format' => 'Please enter a valid end time.',
            'end_time.required'    => 'End time is required.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->date && $this->time) {
                $eventDateTime = $this->date . ' ' . $this->time;
                $eventTime = Carbon::parse($eventDateTime);

                if ($eventTime->lte(now())) {
                    $validator->errors()->add('date', 'The event date and time must be in the future.');
                }
            }

            // Ensure end_time is after start time
            if ($this->date && $this->time && $this->end_time) {
                $start = Carbon::parse($this->date . ' ' . $this->time);
                $end   = Carbon::parse($this->date . ' ' . $this->end_time);

                if ($end->lte($start)) {
                    $validator->errors()->add('end_time', 'End time must be after start time.');
                }
            }
        });
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Combine date + time into full datetime for the 'date' column
        if (isset($validated['date']) && isset($validated['time'])) {
            $validated['date'] = $validated['date'] . ' ' . $validated['time'];
        }

        // Combine date + end_time into a full datetime for the 'end_time' column
        // Use the raw date (before it was mutated above) from the request
        if (isset($validated['end_time'])) {
            $rawDate = $this->input('date');
            $validated['end_time'] = $rawDate . ' ' . $validated['end_time'];
        }

        return $validated;
    }
}

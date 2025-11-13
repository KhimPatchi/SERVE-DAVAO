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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'required_volunteers' => 'required|integer|min:1',
            'skills_required' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'time.date_format' => 'Please enter a valid time format.',
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
        });
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        if (isset($validated['date']) && isset($validated['time'])) {
            $validated['date'] = $validated['date'] . ' ' . $validated['time'];
        }
        
        return $validated;
    }
}
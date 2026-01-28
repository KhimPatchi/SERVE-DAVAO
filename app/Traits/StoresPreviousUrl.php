<?php

namespace App\Traits;

trait StoresPreviousUrl
{
    /**
     * Store the previous URL in session for back navigation
     */
    protected function storePreviousUrl()
    {
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        
        // Only store if it's a different page and from our app
        if ($previousUrl !== $currentUrl && str_contains($previousUrl, config('app.url'))) {
            session(['previous_url' => $previousUrl]);
        }
    }
}
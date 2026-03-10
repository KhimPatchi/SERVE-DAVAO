<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Match Algorithm Weights (Additive Weighted Model)
    |--------------------------------------------------------------------------
    | The sum of these weights should ideally be 1.0 (100%).
    */
    'weights' => [
        'availability' => env('MATCH_WEIGHT_AVAILABILITY', 0.30),
        'similarity'   => env('MATCH_WEIGHT_SIMILARITY', 0.55),
        'location'     => env('MATCH_WEIGHT_LOCATION', 0.15),
    ],

    /*
    |--------------------------------------------------------------------------
    | Match Thresholds
    |--------------------------------------------------------------------------
    | Minimum total score required to display a recommendation.
    | Currently 0.70 (70%) matches.
    */
    'threshold' => env('MATCH_THRESHOLD', 0.70),

    /*
    |--------------------------------------------------------------------------
    | Proximity Settings
    |--------------------------------------------------------------------------
    | Defaut radius in kilometers for the location score.
    */
    'radius_km' => env('MATCH_RADIUS_KM', 1.0),

    /*
    |--------------------------------------------------------------------------
    | Neutral Baselines (Missing Data)
    |--------------------------------------------------------------------------
    | Scores awarded when a user profile is incomplete to prevent 
    | them from being immediately filtered out.
    */
    'baselines' => [
        'availability' => env('MATCH_BASELINE_AVAILABILITY', 0.25),
        'location'     => env('MATCH_BASELINE_LOCATION', 0.10),
    ],
];

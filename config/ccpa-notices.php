<?php

return [

    'table' => 'ccpa_processing_activities',

    /*
     * The business, as it appears in the privacy policy.
     */
    'business' => [
        'name' => env('CCPA_BUSINESS_NAME', env('APP_NAME')),
        'contact' => env('CCPA_BUSINESS_CONTACT'),
        // At least two methods to submit requests, including a toll-free number unless you only
        // operate online and have a direct relationship with consumers (Cal. Civ. Code §1798.130(a)(1)).
        'toll_free_number' => env('CCPA_TOLL_FREE_NUMBER'),
    ],

];

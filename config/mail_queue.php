<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mail Queue
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for the queue connection that should
    | be used by the framework when sending emails. A default configuration has
    | been added for each back-end shipped with Laravel. You are free to add
    | more configurations as required.
    |
    */

    'is_queue' => env('MAIL_QUEUE', false)
];

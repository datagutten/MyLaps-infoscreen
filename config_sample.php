<?php
return [
    'db' => [
        'db_host' => 'localhost',
        'db_name' => 'passings',
        'db_user' => 'database_username',
        'db_password' => 'database_password',
    ],
    'decoders' => [
        'your_track' => [
            'address' => '192.168.0.50',
            'id' => '00ff00',
            'mylaps_id' => 100
        ]
    ],
    'infoscreen' => [
        'round_limit' => 20, // Number of rounds to show
        'only_today' => false, // Only show today's rounds
        'hide_avatars' => false, // Hide driver avatars
        'disable_button' => true, // Show button to disable refresh
        'refresh_interval' => 1, // Refresh interval
    ]
];
<?php
return [
    'driver' => 'database',
    'lifetime' => 120, // Session lifetime in minutes (optional)
    'connection' => env('DB_CONNECTION', 'mysql'), // Database connection name
    'table' => 'sessions', // Session table name
    'lottery' => [2, 100], // Session lottery configuration (optional)
    'cookie' => [
        'encrypted' => true, // Recommended for database driver
    ],
];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This value determines the default "disk" that will be used by the
    | framework to store files. The "local" disk, along with various
    | cloud-based disks like S3, are available to your application.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you can configure multiple disks for various storage drivers.
    | Laravel supports "local", "ftp", "sftp", "s3" drivers for file storage.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),  // Sử dụng thư mục gốc app thay vì "app/private"
            'visibility' => 'private',  // Optional: adjust visibility settings
            'throw' => false,  // Will not throw exceptions for errors
            'report' => false, // Optional: add reporting features if needed
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'), // Store files publicly
            'url' => env('APP_URL').'/storage', // Adjust the URL for serving files
            'visibility' => 'public',  // Ensure files are publicly accessible
            'throw' => false, // Will not throw exceptions for errors
            'report' => false, // Optional: add reporting features if needed
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false, // Will not throw exceptions for errors
            'report' => false, // Optional: add reporting features if needed
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Configure symbolic links to link the "storage" folder to the public
    | directory for easy access. The array keys are the source and values
    | are the target paths.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];

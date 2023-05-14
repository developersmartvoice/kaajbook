<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'user_avtar' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/profile',
        ],

        'login_bg' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/login_bg',
        ],

        'company_logo' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/company_logo',
        ],
        
        'company_sidebar_logo' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/company_sidebar_logo',
        ],

        'sidebar_background_images' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/sidebar_background_images',
        ],

        'translation' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/translation',
        ],

        'project_uploads' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/project',
        ],

        'project_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/project_attachment',
        ],

        'task_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/task_attachment',
        ],

        'defect' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/defect',
        ],

        'defect_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/defect_attachment',
        ],

        'incident_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/incident_attachment',
        ],

        'category_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/category_attachment',
        ],
        
        'article_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/article_attachment',
        ],

        'leave_attachment' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/leave_attachment',
        ],

        'estimate_logo' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/estimate_logo',
        ],

        'invoice_logo' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads/invoice_logo',
        ]

    ],

];

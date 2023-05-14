<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'VipsPM Suite',
    'next' => 'Next Step',
    'back' => 'Previous',
    'finish' => 'Install',
    'forms' => [
        'errorTitle' => 'The Following errors occurred:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Welcome',
        'title'   => 'VipsPM Suite',
        'message' => 'Thank You for Purchasing our VipsPM Suite.',
        'next'    => 'Check Requirements',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Step 1 | Server Requirements',
        'title' => 'Server Requirements',
        'next'    => 'Check Permissions',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Step 2 | Permissions',
        'title' => 'Permissions',
        'next' => 'Configure Environment',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Step 3 | Environment Settings',
            'title' => 'Environment Settings',
            'desc' => 'Please select how you want to configure the apps <code>.env</code> file.',
            'wizard-button' => 'Form Wizard Setup',
            'classic-button' => 'Classic Text Editor',
        ],
        'wizard' => [
            'templateTitle' => 'Step 3 | Environment Settings',
            'title' => 'Configure Environment Settings',
            'tabs' => [
                'environment' => 'Environment',
                'database' => 'Database',
                'application' => 'Application'
            ],
            'form' => [
                'name_required' => 'An environment name is required.',
                'database_name_regex' => 'The database name may only contain letters, numbers, dashes, underscores and dots.',
                'table_prefix_regex' => 'The table prefix may only contain letters, numbers, dashes, underscores and dots.',
                'app_name_label' => 'App Name',
                'app_name_placeholder' => 'ex. VipsPM',
                'app_environment_label' => 'App Environment',
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Development',
                'app_environment_label_qa' => 'QA',
                'app_environment_label_production' => 'Production',
                'app_environment_label_other' => 'Other',
                'app_environment_placeholder_other' => 'Enter Your Environment...',
                'app_debug_label' => 'App Debug',
                'app_debug_label_true' => 'True',
                'app_debug_label_false' => 'False',
                'app_log_level_label' => 'App Log Level',
                'app_log_level_label_debug' => 'Debug',
                'app_log_level_label_info' => 'Info',
                'app_log_level_label_notice' => 'Notice',
                'app_log_level_label_warning' => 'Warning',
                'app_log_level_label_error' => 'Error',
                'app_log_level_label_critical' => 'Critical',
                'app_log_level_label_alert' => 'Alert',
                'app_log_level_label_emergency' => 'Emergency',
                'app_url_label' => 'API URL',
                'app_url_placeholder' => 'Enter API Root URL.',
                'app_url_info' => 'This should be your API root URL.',
                'front_url_label' => 'Front URL',
                'front_url_placeholder' => 'Enter Site Root URL.',
                'front_url_info' => 'This should be your site root URL.',
                'db_connection_label' => 'Database Connection',
                'db_connection_label_mysql' => 'mysql',
                'table_prefix_label' => 'Table Prefix',
                'db_host_label' => 'Database Host',
                'db_host_placeholder' => 'Enter Database Host',
                'db_port_label' => 'Database Port',
                'db_port_placeholder' => 'Enter Database Port',
                'db_name_label' => 'Database Name',
                'db_name_placeholder' => 'Enter Database Name',
                'db_username_label' => 'Database User Name',
                'db_username_placeholder' => 'Enter Database User Name',
                'db_password_label' => 'Database Password',
                'db_password_placeholder' => 'Enter Database Password',

                'app_tabs' => [
                    'more_info' => 'More Info',
                    'broadcasting_title' => 'Broadcasting, Caching, Session, &amp; Queue',
                    'broadcasting_label' => 'Broadcast Driver',
                    'broadcasting_placeholder' => 'Broadcast Driver',
                    'cache_label' => 'Cache Driver',
                    'cache_placeholder' => 'Cache Driver',
                    'session_label' => 'Session Driver',
                    'session_placeholder' => 'Session Driver',
                    'queue_label' => 'Queue Driver',
                    'queue_placeholder' => 'Queue Driver',
                    'redis_label' => 'Redis Driver',
                    'redis_host' => 'Redis Host',
                    'redis_password' => 'Redis Password',
                    'redis_port' => 'Redis Port',

                    'mail_label' => 'Mail',
                    'mail_driver_label' => 'Mail Driver',
                    'mail_driver_placeholder' => 'Enter Mail Driver',
                    'mail_host_label' => 'Mail Host',
                    'mail_host_placeholder' => 'Enter Mail Host',
                    'mail_port_label' => 'Mail Port',
                    'mail_port_placeholder' => 'Enter Mail Port',
                    'mail_username_label' => 'Mail Username',
                    'mail_username_placeholder' => 'Enter Mail Username',
                    'mail_password_label' => 'Mail Password',
                    'mail_password_placeholder' => 'Enter Mail Password',
                    'mail_encryption_label' => 'Mail Encryption',
                    'mail_encryption_placeholder' => 'Enter Mail Encryption',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher App Id',
                    'pusher_app_id_palceholder' => 'Pusher App Id',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Setup Database',
                    'setup_application' => 'Setup Application',
                    'install' => 'Install',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Step 3 | Environment Settings | Classic Editor',
            'title' => 'Classic Environment Editor',
            'save' => 'Save .env',
            'back' => 'Use Form Wizard',
            'install' => 'Save and Install',
        ],
        'success' => 'Your .env file settings have been saved.',
        'errors' => 'Unable to save the .env file, Please create it manually.',
    ],

    'install' => 'Install',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel Installer successfully INSTALLED on ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installation Finished',
        'templateTitle' => 'Installation Finished',
        'finished' => 'Application has been successfully installed.',
        'migration' => 'Migration & Seed Console Output:',
        'console' => 'Application Console Output:',
        'log' => 'Installation Log Entry:',
        'env' => 'Final .env File:',
        'exit' => 'Click here to exit',
    ],

    /**
     *
     * Update specific translations
     *
     */
    'updater' => [
        /**
         *
         * Shared translations.
         *
         */
        'title' => 'Laravel Updater',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Welcome To The Updater',
            'message' => 'Welcome to the update wizard.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Overview',
            'message' => 'There is 1 update.|There are :number updates.',
            'install_updates' => "Install Updates"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Finished',
            'finished' => 'Application\'s database has been successfully updated.',
            'exit' => 'Click here to exit',
        ],

        'log' => [
            'success_message' => 'Laravel Installer successfully UPDATED on ',
        ],
    ],
];

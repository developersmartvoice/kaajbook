<?php

/**
 * Set the active class to the current opened menu.
 *
 * @param  string|array $route
 * @param  string       $className
 * @return string
 */
if ( ! function_exists('isActive'))
{
    function isActive($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }
        if (Route::currentRouteName() == $route) {
            return $className;
        }
        if (strpos(URL::current(), $route)) return $className;
    }
}

/**
 * Get installer configs.
 *
 * @return array
 */
if ( ! function_exists('getConfigs'))
{
    function getConfigs()
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Server Requirements
            |--------------------------------------------------------------------------
            |
            | This is the default Laravel server requirements, you can add as many
            | as your application require, we check if the extension is enabled
            | by looping through the array and run "extension_loaded" on it.
            |
            */
            'core' => [
                'minPhpVersion' => '7.1.3'
            ],
            'final' => [
                'key' => true,
                'publish' => false
            ],    
            'requirements' => [
                'php' => [
                    'openssl',
                    'pdo',
                    'mbstring',
                    'tokenizer',
                    'JSON',
                    'cURL',
                    'mysqli',
                    'gd',
                    // 'pdo_mysql',
                    // 'zip',
                ],
                'apache' => [
                    'mod_rewrite',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Folders Permissions
            |--------------------------------------------------------------------------
            |
            | This is the default Laravel folders permissions, if your application
            | requires more permissions just add them to the array list bellow.
            |
            */
            'permissions' => [
                'bootstrap/cache/'       => '777',
                'public/uploads/'        => '777',
                // 'public/vendor/'        => '777',
                'storage/app/'           => '777',
                'storage/framework/'     => '777',
                'storage/logs/'          => '777',
                'install.sql'            => '666',
                '.env'                   => '666'
            ],

            /*
            |--------------------------------------------------------------------------
            | Environment Form Wizard Validation Rules & Messages
            |--------------------------------------------------------------------------
            |
            | This are the default form vield validation rules. Available Rules:
            | https://laravel.com/docs/5.7/validation#available-validation-rules
            |
            */
            'environment' => [
                'form' => [
                    'rules' => [
                        'app_name'              => 'required|string|max:50',
                        'environment'           => 'required|string|max:50',
                        'environment_custom'    => 'required_if:environment,other|max:50',
                        'app_debug'             => 'required',
                        'app_log_level'         => 'required|string|max:50',
                        'app_url'               => 'required|url',
                        'front_url'             => 'required|url|different:app_url',
                        'database_hostname'     => 'required|string|max:50',
                        'database_port'         => 'required|numeric',
                        'database_name'         => 'required|string|max:50|regex:/^[A-Za-z0-9_.-]+$/',
                        'database_username'     => 'required|string|max:50',
                        'mail_driver'           => 'nullable|string|max:50',
                        'mail_host'             => 'nullable|string|max:50',
                        'mail_port'             => 'nullable|numeric',
                        'mail_username'         => 'nullable|string|max:50',
                        'mail_password'         => 'nullable|string|max:50',
                        'mail_encryption'       => 'nullable|string|max:50',
                        'table_prefix'          => 'required|string|max:10|regex:/^[A-Za-z0-9_-]+$/',
                        'site_email'            => 'required|email',
                        'support_email'         => 'required|email',
                        'timezone'              => 'required'
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Sites Form Validation Rules & Messages
            |--------------------------------------------------------------------------
            |
            | This are the default form vield validation rules. Available Rules:
            | https://laravel.com/docs/5.7/validation#available-validation-rules
            |
            */
            'sites' => [
                'form' => [
                    'rules' => [
                        'username' => 'required|min:3|max:30|regex:/^\S*$/|unique:'.config('core.acl.users_table').',username,NULL,id,deleted_at,NULL',
                        'firstname' => 'required|max:20',
                        'lastname' => 'required|max:20',
                        'currency' => 'required',
                        'email' => 'required|email|unique:'.config('core.acl.users_table').',email,NULL,id,deleted_at,NULL',
                        'password' => 'required|min:3|max:30'
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Installed Middlware Options
            |--------------------------------------------------------------------------
            | Different available status switch configuration for the
            | canInstall middleware located in `canInstall.php`.
            |
            */
            'installed' => [
                'redirectOptions' => [
                    'route' => [
                        'name' => 'index',
                        'data' => [],
                    ],
                    'abort' => [
                        'type' => '404',
                    ],
                    'dump' => [
                        'data' => 'Dumping a not found message.',
                    ]
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Selected Installed Middlware Option
            |--------------------------------------------------------------------------
            | The selected option for what happens when an installer intance has been
            | Default output is to `/resources/views/error/404.blade.php` if none.
            | The available middleware options include:
            | route, abort, dump, 404, default, ''
            |
            */
            'installedAlreadyAction' => 'route',

            /*
            |--------------------------------------------------------------------------
            | Updater Enabled
            |--------------------------------------------------------------------------
            | Can the application run the '/update' route with the migrations.
            | The default option is set to False if none is present.
            | Boolean value
            |
            */
            'updaterEnabled' => 'true'
        ];
    }
}

/**
 * Print data.
 *
 * @param  array $data
 * @param  string $exit
 */
if (! function_exists('pr')) {
    function pr($data, $exit = 0) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if($exit)
            exit;
    }
}

/**
 * Update .env file.
 *
 * @param  string $name
 * @param  string $new_value
 * @param  string $old_value
 */
if (! function_exists('setEnv')) {
    function setEnv($name, $new_value, $old_value)
    {
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            file_put_contents($envPath, str_replace(
                $name . '=' . $old_value, $name . '=' . $new_value, file_get_contents($envPath)
            ));
        }
    }
}

/**
 * Get next autoincrement id.
 *
 * @param  string $table
 * @return string
 */
if (! function_exists('nextAutoID')) {
    function nextAutoID($table) {
        $statement = DB::select("show table status like '{$table}'");
        return $statement[0]->Auto_increment;
        // return DB::table($table)->max('id') + 1;
    }
}



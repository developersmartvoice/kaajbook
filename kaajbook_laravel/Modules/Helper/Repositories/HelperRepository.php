<?php

namespace Modules\Helper\Repositories;

use Auth;
use Carbon\Carbon;
use File;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Announcement\Models\Announcement;
use Modules\CustomField\Models\CustomField;
use Modules\Defect\Models\Defect;
use Modules\Incident\Models\Incident;
use Modules\KnowledgeBaseArticle\Models\KnowledgeBaseArticle;
use Modules\Projects\Models\Project;
use Modules\Task\Models\Task;
use Modules\Team\Models\Team;
use Modules\User\Models\Department\DepartmentRoleMenu;
use Modules\User\Models\User\User;
use Symfony\Component\Process\Process;

/**
 * Class HelperRepository
 *
 * Helper functionas
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class HelperRepository
{

    /**
     * Get countries.
     *
     * @return Response
     */
    public function getCountries()
    {
        return DB::table(config('core.acl.countries_table'))->get();
    }

    /**
     * Get locals.
     *
     * @return Response
     */
    public function getLocales()
    {
        return DB::table(config('core.acl.locales_table'))->get();
    }

    /**
     * Get languages.
     *
     * @return Response
     */
    public function getLanguages()
    {
        return DB::table(config('core.acl.languages_table'))->get();
    }

    /**
     * Get time zones.
     *
     * @return Response
     */
    public function getTimezones()
    {
        return array(
            'Pacific/Midway'       => "(GMT-11:00) Midway Island",
            'US/Samoa'             => "(GMT-11:00) Samoa",
            'US/Hawaii'            => "(GMT-10:00) Hawaii",
            'US/Alaska'            => "(GMT-09:00) Alaska",
            'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana'      => "(GMT-08:00) Tijuana",
            'US/Arizona'           => "(GMT-07:00) Arizona",
            'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
            'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
            'America/Mexico_City'  => "(GMT-06:00) Mexico City",
            'America/Monterrey'    => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
            'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
            'America/Bogota'       => "(GMT-05:00) Bogota",
            'America/Lima'         => "(GMT-05:00) Lima",
            'America/Caracas'      => "(GMT-04:30) Caracas",
            'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz'       => "(GMT-04:00) La Paz",
            'America/Santiago'     => "(GMT-04:00) Santiago",
            'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            'America/Godthab'      => "(GMT-03:00) Greenland",
            'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
            'Atlantic/Azores'      => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca'    => "(GMT) Casablanca",
            'Europe/Dublin'        => "(GMT) Dublin",
            'Europe/Lisbon'        => "(GMT) Lisbon",
            'Europe/London'        => "(GMT) London",
            'Africa/Monrovia'      => "(GMT) Monrovia",
            'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
            'Europe/Berlin'        => "(GMT+01:00) Berlin",
            'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
            'Europe/Brussels'      => "(GMT+01:00) Brussels",
            'Europe/Budapest'      => "(GMT+01:00) Budapest",
            'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
            'Europe/Madrid'        => "(GMT+01:00) Madrid",
            'Europe/Paris'         => "(GMT+01:00) Paris",
            'Europe/Prague'        => "(GMT+01:00) Prague",
            'Europe/Rome'          => "(GMT+01:00) Rome",
            'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
            'Europe/Skopje'        => "(GMT+01:00) Skopje",
            'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
            'Europe/Vienna'        => "(GMT+01:00) Vienna",
            'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
            'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
            'Europe/Athens'        => "(GMT+02:00) Athens",
            'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
            'Africa/Cairo'         => "(GMT+02:00) Cairo",
            'Africa/Harare'        => "(GMT+02:00) Harare",
            'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
            'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
            'Europe/Kiev'          => "(GMT+02:00) Kyiv",
            'Europe/Minsk'         => "(GMT+02:00) Minsk",
            'Europe/Riga'          => "(GMT+02:00) Riga",
            'Europe/Sofia'         => "(GMT+02:00) Sofia",
            'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
            'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
            'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
            'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
            'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
            'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
            'Europe/Moscow'        => "(GMT+03:00) Moscow",
            'Asia/Tehran'          => "(GMT+03:30) Tehran",
            'Asia/Baku'            => "(GMT+04:00) Baku",
            'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
            'Asia/Muscat'          => "(GMT+04:00) Muscat",
            'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
            'Asia/Kabul'           => "(GMT+04:30) Kabul",
            'Asia/Karachi'         => "(GMT+05:00) Karachi",
            'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
            'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty'          => "(GMT+06:00) Almaty",
            'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
            'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth'      => "(GMT+08:00) Perth",
            'Asia/Singapore'       => "(GMT+08:00) Singapore",
            'Asia/Taipei'          => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
            'Asia/Seoul'           => "(GMT+09:00) Seoul",
            'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
            'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
            'Australia/Darwin'     => "(GMT+09:30) Darwin",
            'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
            'Australia/Canberra'   => "(GMT+10:00) Canberra",
            'Pacific/Guam'         => "(GMT+10:00) Guam",
            'Australia/Hobart'     => "(GMT+10:00) Hobart",
            'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney'     => "(GMT+10:00) Sydney",
            'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
            'Asia/Magadan'         => "(GMT+12:00) Magadan",
            'Pacific/Auckland'     => "(GMT+12:00) Auckland",
            'Pacific/Fiji'         => "(GMT+12:00) Fiji",
            'America/Sao_Paulo'    => "(GMT-03:00) Brasilia",
            'Africa/Lagos'         => "(GMT+01:00) West Central Africa",
            'Africa/Johannesburg'  => "(GMT+02:00) Pretoria",
            'Asia/Calcutta'        => "(GMT+05:30) Chennai",
            'Asia/Colombo'         => "(GMT+06:00) Sri Jayawardenepura",
            'Asia/Rangoon'         => "(GMT+06:30) Rangoon",
            'Pacific/Kwajalein'    => "(GMT-12:00) International Date Line West",
            'Asia/Kamchatka'       => "(GMT+12:00) Kamchatka",
            'Pacific/Tongatapu'    => "(GMT+13:00) Nuku'alofa"
        );
    }

    /**
     * Get days.
     *
     * @return Response
     */
    public function getDays()
    {
        return [
            1 => 'Saturday',
            2 => 'Sunday',
            3 => 'Monday',
            4 => 'Tuesday',
            5 => 'Wednesday',
            6 => 'Thursday',
            7 => 'Friday'
        ];
    }

    /**
     * Get currencies.
     *
     * @return Response
     */
    public function getCurrencies()
    {
        return DB::table(config('core.acl.currency'))->get();
    }

    /**
     * Get role/departments/menus.
     *
     * @return Response
     */
    public function getRoleDepartmentMenu()
    {
        $roles = Auth::user()->roles()->get()->pluck('id');
        $departments = Auth::user()->departments()->get()->pluck('id');

        $assigned_menu_roles_table = config('core.acl.assigned_menu_roles_table');
        $menu_table = config('core.acl.menu_table');
        $roles_table = config('core.acl.roles_table');

        return DepartmentRoleMenu::join($menu_table, $assigned_menu_roles_table . '.menu_id', '=', $menu_table.'.id')
            ->join($roles_table, $assigned_menu_roles_table . '.role_id', '=', $roles_table.'.id')
            ->whereIn($assigned_menu_roles_table. '.department_id', $departments)
            ->whereIn($assigned_menu_roles_table. '.role_id', $roles)
            ->where($menu_table. '.status', 1)
            ->orderBy('order')
            ->get();
    }

    /**
     * Remove overdue annoucement.
     *
     * @return void
     */
    public function removeOverdueAnnoucement()
    {
        $currnetDate = date("Y-m-d");
        $matchThese = [['end_date','<',$currnetDate],['status','!=','2']];
        $Announcement = Announcement::where($matchThese)->update(['status' => 2]);
    }

    /**
     * Save custom field.
     *
     * @param int   $id      [Row id]
     * @param int   $edit_id [Edit row id]
     * @param array $input   [Params for custom field]
     *
     * @return Response
     */
    public function saveCustomField($id, $edit_id = null, $input = [])
    {
        $modelObj = [];
        $custom_field = CustomField::with('forms')->where('form_id', $id)->first();
        $custom_fields = CustomField::where('form_id', $id)->get();

        if (!empty($custom_fields) && !empty($custom_field->forms)) {
            $table_name = $custom_field->forms->table_name;
            
            foreach ($custom_fields as $key => $value) {
                // --
                // Check status.
                if ($value->status) {
                    $name = $value->field_column;
                    // --
                    // Check for field exits.
                    if (isset($input[$name])) {
                        if (!Schema::hasColumn($table_name, $name)) {
                            // --
                            // Check field type.
                            switch ($value->field_type) {
                                case 'text':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->string($name, 255)->nullable();
                                        }
                                    );
                                    break;
                                case 'textarea':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->text($name)->nullable();
                                        }
                                    );
                                    break;
                                case 'dropdown':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->string($name)->nullable();
                                        }
                                    );
                                    break;
                                case 'date':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->date($name)->nullable();
                                        }
                                    );
                                    break;
                                case 'checkbox':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->boolean($name)->default(true);
                                        }
                                    );
                                    break;
                                case 'numeric':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->float($name)->nullable();
                                        }
                                    );
                                    break;
                                case 'url':
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->string($name)->nullable();
                                        }
                                    );
                                    break;
                                default:
                                    \Schema::table(
                                        $table_name,
                                        function (Blueprint $table) use ($name) {
                                            $table->string($name)->nullable();
                                        }
                                    );
                                    break;
                            }
                        }

                        // --
                        // Save data in DB
                        switch ($id) {
                            case 1:
                                $modelObj = Project::findOrFail($edit_id);
                                $modelObj->$name = $input[$name];
                                $modelObj->save();
                                break;
                            case 2:
                                $modelObj = Task::findOrFail($edit_id);
                                $modelObj->$name = $input[$name];
                                $modelObj->save();
                                break;
                            case 3:
                                $modelObj = Defect::findOrFail($edit_id);
                                $modelObj->$name = $input[$name];
                                $modelObj->save();
                                break;
                            case 4:
                                $modelObj = Incident::findOrFail($edit_id);
                                $modelObj->$name = $input[$name];
                                $modelObj->save();
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Get data for dashboard.
     *
     * @param Request $request [Request for get dashboard data]
     *
     * @return Response
     */
    public function getDashboardData($request)
    {
        $user = Auth::user();
        $length = $request->get('length');
        $now = Carbon::now();

        $data['total_team'] = Team::count();
        $data['total_user'] = User::where('is_client', false)->count();
        $data['total_client'] = User::where('is_client', true)->count();
        $data['total_kb_article'] = KnowledgeBaseArticle::count();

        // Today user activities.
        $data['today_activities'] = $user->userActivity()->with([
            'user' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            }
        ])
        ->where('created_at', '>=', Carbon::now()->subDay())
        ->orderBy('id', 'desc')
        ->take($length)
        ->get();

        // Announcement.
        $data['announcement'] = Announcement::whereNotIn('status', [2])
            ->orderBy('start_date', 'asc')
            ->take($length)
            ->get();

        // Meeting.
        $data['meetings'] = $user->meetings()
            ->where('start_date', '>=', date('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->take($length)
            ->get();
        
        return $data;
    }

    /**
     * Reset default database.
     *
     * @return Void
     */
    public function resetDatabase(){
        set_time_limit(0);
        // Backup DB
        $path = public_path() . '/database_backup';
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $configs = config('database.connections.mysql');
        $filename = 'database_'. date('Y\-m\-j\_H\-i\-s').'.sql';
        $storage_path =  $path . '/'.$filename;
        $process = new Process('mysqldump -u '.$configs['username'].' -p'.$configs['password'].' '.$configs['database'].' > '.$storage_path);
        $process->setTimeout(2 * 3600);
        $process->setIdleTimeout(10 * 60);
        $process->run();
        // Reet DB
        // php artisan migrate:reset
        Schema::dropAllTables();
        DB::unprepared(file_get_contents(base_path('public/database/vipspm.sql')));
        echo "Database backup and reset successfully.";
    }
}

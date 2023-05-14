<?php

namespace Modules\LeaveType\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\LeaveType\Models\LeaveType;

/**
 * Class LeaveTypeDatabaseSeeder
 *
 * The Seeder is Defined for Leave Types.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\LeaveType
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class LeaveTypeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        LeaveType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $data = [
            [
                'leave_type' => 'Personal',
                'color' => '#1ab394'
            ],
            [
                'leave_type' => 'Casual',
                'color' => '#f50606'
            ],
            [
                'leave_type' => 'Sick',
                'color' => '#06f509'
            ],
            [
                'leave_type' => 'Maternity',
                'color' => '#0614f5'
            ],
            [
                'leave_type' => 'Earned',
                'color' => '#ee06f5'
            ]
        ];

        LeaveType::insert($data);
    }
}

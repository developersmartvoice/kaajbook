<?php

namespace Modules\EmailGroup\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Modules\EmailGroup\Models\EmailGroup;
use DB;

/**
 * Class EmailGroupDatabaseSeeder
 *
 * The seeder is Defined for Email Group.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailGroup
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailGroupDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // --
        // Email groups
        DB::table(config('core.acl.email_group_table'))->delete();
        $data = [
            [
                'id' => 1,
                'email_group_name' => 'Account Emails'
            ],
            [
                'id' => 2,
                'email_group_name' => 'Project Emails'
            ],
            [
                'id' => 3,
                'email_group_name' => 'Task Emails'
            ],
            [
                'id' => 4,
                'email_group_name' => 'Defects Email'
            ],
            [
                'id' => 5,
                'email_group_name' => 'Incidents Email'
            ],
            [
                'id' => 6,
                'email_group_name' => 'Appointment Emails'
            ],
            [
                'id' => 7,
                'email_group_name' => 'Leave Emails'
            ],
            [
                'id' => 8,
                'email_group_name' => 'Estimate Emails'
            ],
            [
                'id' => 9,
                'email_group_name' => 'Invoice Emails'
            ],
            [
                'id' => 10,
                'email_group_name' => 'Payment Emails'
            ],
            [
                'id' => 11,
                'email_group_name' => 'Meeting Emails'
            ]
        ];
        EmailGroup::insert($data);
    }
}

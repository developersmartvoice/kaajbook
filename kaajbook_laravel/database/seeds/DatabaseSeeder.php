<?php

use Illuminate\Database\Seeder;
use Modules\CustomField\Database\Seeders\CustomFieldDatabaseSeeder;
use Modules\EmailGroup\Database\Seeders\EmailGroupDatabaseSeeder;
use Modules\EmailTemplate\Database\Seeders\EmailTemplateDatabaseSeeder;
use Modules\Estimate\Database\Seeders\EstimateDatabaseSeeder;
use Modules\Helper\Database\Seeders\HelperDatabaseSeeder;
use Modules\Invoice\Database\Seeders\InvoiceDatabaseSeeder;
use Modules\LeaveType\Database\Seeders\LeaveTypeDatabaseSeeder;
use Modules\Menu\Database\Seeders\MenuDatabaseSeeder;
use Modules\Setting\Database\Seeders\SettingDatabaseSeeder;
use Modules\Translation\Database\Seeders\TranslationDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\Helper\Database\Seeders\CountryDatabaseTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountryDatabaseTableSeeder::class);
        $this->call(HelperDatabaseSeeder::class);
        $this->call(CustomFieldDatabaseSeeder::class);
        $this->call(EmailGroupDatabaseSeeder::class);
        $this->call(EmailTemplateDatabaseSeeder::class);
        $this->call(TranslationDatabaseSeeder::class);
        $this->call(MenuDatabaseSeeder::class);
        $this->call(UserDatabaseSeeder::class);
        // $this->call(SettingDatabaseSeeder::class);
        $this->call(LeaveTypeDatabaseSeeder::class);
        $this->call(EstimateDatabaseSeeder::class);
        $this->call(InvoiceDatabaseSeeder::class);
    }
}

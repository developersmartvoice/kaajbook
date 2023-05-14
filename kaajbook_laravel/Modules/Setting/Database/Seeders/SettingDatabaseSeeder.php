<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Models\Setting;

/**
 * Class SettingDatabaseSeeder
 *
 * The Seeder is Defined for Setting.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Setting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --
        // Save settings
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting;
        }

        $setting->id = 1;
        $setting->company_name = config('app.name');
        $setting->company_from_email = config('mail.from.address');
        $setting->smtp_protocol = config('mail.driver');
        $setting->smtp_host = config('mail.host');
        $setting->smtp_user = config('mail.username');
        $setting->smtp_password = config('mail.password');
        $setting->smtp_port = config('mail.port');
        $setting->smtp_encryption = config('mail.encryption');
        $setting->currency_id = 32;
        $setting->product_version = config('core.product_version');
        $setting->save();
    }
}

<?php

namespace Modules\Invoice\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Invoice\Models\InvoiceSetting;

/**
 * Class InvoiceDatabaseSeeder
 *
 * The Seeder is Defined for Invoice.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Invoice
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class InvoiceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Estimate setting seeding.
        InvoiceSetting::truncate();
        InvoiceSetting::insert([
            "terms_conditions" => "Thank you for your business. Please process this invoice within the due date."
        ]);
    }
}

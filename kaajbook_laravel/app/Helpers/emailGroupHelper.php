<?php

namespace App\Helpers;

/**
 * Class EmailGroupHelper
 *
 * Email group helper function
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   App\Helpers
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license
 * @version   Release: @1.0@
 * @link
 * @since     Class available since Release 1.0
 */
class EmailGroupHelper
{
    /**
     * Get email group.
     *
     * @return array
     */
    public static function getEmailGroup()
    {
        return array(
            1 => 'Account Emails',
            2 => 'Invoicing Emails',
            3 => 'Tasks Emails',
            4 => 'Defect Emails',
            5 => 'Project Emails',
            6 => 'Incident Emails'
        );
    }
}

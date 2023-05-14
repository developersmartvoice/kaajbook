<?php

namespace Modules\Setting\Models\Attribute;

/**
 * Trait SettingAttribute
 *
 * The Attribute is Defined for Setting.
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
trait SettingAttribute
{
    /**
     * This mutator automatically format last cron job run.
     *
     * @param Date $value [Start date]
     *
     * @return Date
     */
    public function getLastCronjobRunAttribute($value)
    {
        return ($value == null) ? '' : date('Y-m-d H:i:s',$value);
    }
}

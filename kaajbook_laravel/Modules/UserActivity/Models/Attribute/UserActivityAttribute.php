<?php

namespace Modules\UserActivity\Models\Attribute;

use Carbon\Carbon;

/**
 * Trait UserActivityAttribute
 *
 * The Attribute is Defined for UserActivity.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\UserActivity
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait UserActivityAttribute
{
    /**
     * This mutator automatically calculate diff for humans.
     *
     * @return String
     */
    public function getDiffForHumansAttribute()
    {
        $nowTime = Carbon::now();
        $oldTime = Carbon::parse($this->created_at);
        return $oldTime->diffForHumans($nowTime);
    }

    /**
     * This mutator automatically convert capital to space.
     *
     * @param String $value [Module name]
     *
     * @return String
     */
    public function getModuleAttribute($value)
    {
        return ltrim(preg_replace('/[A-Z]/', ' $0', $value));
    }
}

<?php

namespace Modules\Team\Models\Attribute;

/**
 * Trait TeamAttribute
 *
 * The Attribute is Defined For Team.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Team
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait TeamAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Team name]
     *
     * @return String
     */
    public function setTeamNameAttribute($value)
    {
        $this->attributes['team_name'] = trim(ucwords($value));
    }
}

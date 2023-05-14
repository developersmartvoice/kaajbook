<?php

namespace Modules\User\Models\Role\Attribute;

use Illuminate\Support\Str;

/**
 * Trait RoleAttribute
 *
 * The Attribute is Defined For Role.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait RoleAttribute
{
     /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Role name]
     *
     * @return String
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(ucwords($value));
        $this->attributes['slug'] = Str::slug($value, '_');
    }
}

<?php

namespace Modules\Item\Models\Attribute;

/**
 * Trait ItemAttribute
 *
 * The Attribute is Defined for Item.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Item
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
trait ItemAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase.
     *
     * @param String $value [Item name]
     *
     * @return String
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(ucwords($value));
    }
}

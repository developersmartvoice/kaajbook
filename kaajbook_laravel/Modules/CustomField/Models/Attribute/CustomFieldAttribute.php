<?php

namespace Modules\CustomField\Models\Attribute;

/**
 * Trait CustomField
 *
 * The Attribute is Defined For CustomField.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\CustomField
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait CustomFieldAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Field label]
     *
     * @return String
     */
    public function setFieldLabelAttribute($value)
    {
        $this->attributes['field_label'] = trim(ucwords($value));
    }
}

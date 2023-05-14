<?php

namespace Modules\KnowledgeBaseCategory\Models\Attribute;

/**
 * Trait KnowledgeBaseCategoryAttribute
 *
 * The Attribute is Defined For KnowledgeBaseCategory.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\KnowledgeBaseCategory
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait KnowledgeBaseCategoryAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Category name]
     *
     * @return String
     */
    public function setCategoryNameAttribute($value)
    {
        $this->attributes['category_name'] = trim(ucwords($value));
    }
}

<?php

namespace Modules\Item\Models\Relationship;

/**
 * Trait ItemRelationship
 *
 * The Relationship is Defined for Item.
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
trait ItemRelationship
{
    /**
     * Many-to-Many relations with taxes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxes()
    {
        return $this->belongsToMany(
            config('core.acl.tax_model'), 
            config('core.acl.item_tax_table')
        );
    }
}

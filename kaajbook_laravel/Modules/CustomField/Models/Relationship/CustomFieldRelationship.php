<?php

namespace Modules\CustomField\Models\Relationship;

/**
 * Trait CustomFieldRelationship
 *
 * The Relationship is Defined For CustomField.
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
trait CustomFieldRelationship
{
    /**
     * One-to-Many relations with form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function forms()
    {
        return $this->belongsTo(config('core.acl.form'), 'form_id');
    }
}

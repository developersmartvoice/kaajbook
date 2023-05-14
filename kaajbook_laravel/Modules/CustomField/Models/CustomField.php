<?php

namespace Modules\CustomField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\CustomField\Models\Attribute\CustomFieldAttribute;
use Modules\CustomField\Models\Relationship\CustomFieldRelationship;

/**
 * Class CustomField
 *
 * The Model is Defined for CustomField.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\CustomField
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CustomField extends Model
{
    use SoftDeletes, CustomFieldAttribute, CustomFieldRelationship;
    const MODULE_NAME = 'CustomField';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id',
        'field_label',
        'field_column',
        'help_text',
        'is_required',
        'show_on_details',
        'field_type',
        'default_value',
        'status'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Instantiate a new model instance.
     *
     * @param Array $attributes [object]
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->table = config('core.acl.custom_fields');
    }

    /**
     * Get module view fields.
     *
     * @param String $query      [object]
     * @param Int    $form_id [Form Id]
     *
     * @return String
     */
    public function scopeGetViewFields($query, $form_id)
    {
        return $query->where("status",true)
                ->select('field_label','field_column','default_value','field_type')
                ->where("form_id",$form_id)
                ->where("show_on_details",1);
    }
}

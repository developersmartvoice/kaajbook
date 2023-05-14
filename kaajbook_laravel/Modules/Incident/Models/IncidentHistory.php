<?php

namespace Modules\Incident\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IncidentHistory
 *
 * The Model is Defined for Incident History.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Incident
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentHistory extends Model
{
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
        'incident_id',
        'description',
        'created_by_id',
        'commented_by_id',
        'solved_by_id',
        'closed_by_id',
        'updated_by_id'
    ];

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
        
        $this->table = config('core.acl.incident_history_table');
    }

    /**
     * Get the incidents that owns the history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function incidents()
    {
        return $this->belongsTo(config('core.acl.incidents_model'));
    }
}

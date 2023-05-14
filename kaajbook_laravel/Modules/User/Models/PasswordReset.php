<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReset
 *
 * The Model is Defined for Password Reset.
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
class PasswordReset extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token'];

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
        
        $this->table = config('core.acl.password_resets_table');
    }

    /**
     * This mutator automatically set date format for updated date.
     *
     * @param String $value [Updated date]
     *
     * @return void.
     */
    public function setUpdatedAtAttribute($value)
    {
        // to disable updated_at
    }
}

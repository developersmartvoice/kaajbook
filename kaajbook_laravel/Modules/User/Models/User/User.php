<?php

namespace Modules\User\Models\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Modules\User\Models\User\Access\UserAccess;
use Modules\User\Models\User\Attribute\UserAttribute;
use Modules\User\Models\User\Relationship\UserRelationship;

/**
 * Class User
 *
 * The Model is Defined for User.
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
class User extends Model implements
    JWTSubject,
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable,
    Authorizable,
    CanResetPassword,
    SoftDeletes,
    UserAccess,
    UserAttribute,
    UserRelationship;

    const MODULE_NAME = 'User';

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
        'user_generated_id',
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'is_super_admin',
        'is_client',
        'email_verified',
        'online_status',
        'is_active',
        'avatar',
        'last_ip',
        'last_login',
        'emp_id',
        'email_verification_code',
        'city',
        'state',
        'country',
        'postal_code',
        'language',
        'address',
        'phone',
        'mobile',
        'skype',
        'joining_date',
        'gender',
        'dob',
        'father_name',
        'mother_name',
        'maritial_status',
        'permission',
        'can_view_all_users',
        'primary_manager',
        'secondary_manager'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The list of attributes to cast.
     *
     * @var array
     */
    protected $casts = ['permission' => 'json'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

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
        
        $this->table = config('core.acl.users_table');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

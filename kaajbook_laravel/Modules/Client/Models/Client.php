<?php

namespace Modules\Client\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Client\Models\Attribute\ClientAttribute;

/**
 * Class Client
 *
 * @package Modules\Client
 */
/**
 * Class Client
 *
 * The Model is Defined for Client.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Client
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Client extends Model
{
    use SoftDeletes, ClientAttribute;
    const MODULE_NAME = 'Client';
    
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
        'user_id',
        'company_name',
        'company_email',
        'company_phone',
        'company_mobile',
        'company_zipcode',
        'company_city',
        'company_country',
        'company_fax',
        'company_address',
        'website',
        'skype_id',
        'facebook',
        'twitter',
        'linkedin',
        'hosting_company',
        'host_name',
        'host_username',
        'host_password',
        'host_port'
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
        
        $this->table = config('core.acl.clients_table');
    }
}

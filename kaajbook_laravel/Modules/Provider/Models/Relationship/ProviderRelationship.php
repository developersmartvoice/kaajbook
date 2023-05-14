<?php

namespace Modules\Provider\Models\Relationship;

/**
 * Trait ProviderRelationship
 *
 * The relationship is defined for provider.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Provider
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ProviderRelationship
{
	/**
     * Remove provider appointment.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($provider) {
            $provider->appointments()->delete();
        });
    }

    /**
     * Has-Many relations with appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function appointments()
    {
        return $this->hasMany(config('core.acl.appointment_model'));
    }
}

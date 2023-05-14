<?php

namespace Modules\FileBrowser\Models;

use Illuminate\Database\Eloquent\Model;

use Modules\FileBrowser\Models\Relationship\FileRelationship;

/**
 * Class File
 *
 * The Model is Defined for File.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\File
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class File extends Model
{
    use FileRelationship;
    
    const MODULE_NAME = 'File';

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
    protected $fillable = [];

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
        
        $this->table = config('core.acl.files_table');
    }
}

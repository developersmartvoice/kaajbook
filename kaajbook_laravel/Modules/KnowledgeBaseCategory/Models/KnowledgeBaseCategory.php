<?php

namespace Modules\KnowledgeBaseCategory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\KnowledgeBaseCategory\Models\Attribute\KnowledgeBaseCategoryAttribute;
use Modules\KnowledgeBaseCategory\Models\Relationship\KnowledgeBaseCategoryRelationship;

/**
 * Class KnowledgeBaseCategory
 *
 * The Model is Defined for Knowledgebase Categories.
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
class KnowledgeBaseCategory extends Model
{
    use SoftDeletes, KnowledgeBaseCategoryAttribute, KnowledgeBaseCategoryRelationship;

    const MODULE_NAME = 'KnowledgeBaseCategory';

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
        'category_name',
        'category_logo'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        
        $this->table = config('core.acl.knowledge_base_category_table');
    }
}

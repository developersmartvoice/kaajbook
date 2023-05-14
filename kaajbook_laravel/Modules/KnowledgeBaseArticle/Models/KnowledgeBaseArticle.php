<?php

namespace Modules\KnowledgeBaseArticle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\KnowledgeBaseArticle\Models\Attribute\KnowledgeBaseArticleAttribute;
use Modules\KnowledgeBaseArticle\Models\Relationship\KnowledgeBaseArticleRelationship;

/**
 * Class KnowledgeBaseArticle
 *
 * The Model is Defined for Knowledgebase Articles.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\KnowledgeBaseArticle
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class KnowledgeBaseArticle extends Model
{
    use SoftDeletes, KnowledgeBaseArticleAttribute, KnowledgeBaseArticleRelationship;

    const MODULE_NAME = 'KnowledgeBaseArticle';

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
        'category_id',
        'article_title',
        'description',
        'file_name'
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
        
        $this->table = config('core.acl.knowledge_base_article_table');
    }
}

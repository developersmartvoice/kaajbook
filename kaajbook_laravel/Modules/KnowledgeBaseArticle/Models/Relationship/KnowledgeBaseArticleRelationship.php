<?php

namespace Modules\KnowledgeBaseArticle\Models\Relationship;

/**
 * Trait KnowledgeBaseArticleRelationship
 *
 * The Relationship is Defined for Knowledgebase Articles.
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
trait KnowledgeBaseArticleRelationship
{
    /**
     * Many-to-Many relations with category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function category()
    {
        return $this->belongsTo(
            config('core.acl.knowledge_base_category_model'),
            'id'
        );
    }
}

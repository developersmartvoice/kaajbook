<?php

namespace Modules\KnowledgeBaseCategory\Models\Relationship;

/**
 * Trait KnowledgeBaseCategoryRelationship
 *
 * The Relationship is Defined for Knowledgebase Categories.
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
trait KnowledgeBaseCategoryRelationship
{
    /**
     * Has-Many relations with articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function articles()
    {
        return $this->hasMany(
            config('core.acl.knowledge_base_article_model'),
            'category_id'
        );
    }
}

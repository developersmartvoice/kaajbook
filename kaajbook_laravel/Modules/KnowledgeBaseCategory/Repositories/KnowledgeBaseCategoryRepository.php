<?php

namespace Modules\KnowledgeBaseCategory\Repositories;

use Modules\KnowledgeBaseCategory\Models\KnowledgeBaseCategory;
use Modules\KnowledgeBaseArticle\Models\KnowledgeBaseArticle;
use Modules\Helper\Helpers\UploadFileHelper;

use Auth;

/**
 * Class KnowledgeBaseCategoryRepository
 *
 * Knowledge Base Category create, update, delete and view.
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
class KnowledgeBaseCategoryRepository
{
    protected $uploadHelper;

    /**
     * Instantiate a new repository instance.
     *
     * @param UploadFileHelper $uploadHelper [Object]
     *
     * @return void
     */
    public function __construct(UploadFileHelper $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function findAll()
    {
        return KnowledgeBaseCategory::with('articles')->get();
    }

    /**
     * Display the specified resource from storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return KnowledgeBaseCategory::with('articles')->find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create KB category]
     *
     * @return boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $user = Auth::user()->id;
        $knowledgeBaseCategory = new KnowledgeBaseCategory;

        // --
        // Upload attachment
        $input['category_logo'] = $this->uploadHelper->uploadAttachment(
            $input,
            'category_attachment'
        );
        $knowledgeBaseCategory->user_id = $user;
        $knowledgeBaseCategory->fill($input);

        if ($knowledgeBaseCategory->save()) {
            // --
            // Add activities
            createUserActivity(
                KnowledgeBaseCategory::MODULE_NAME,
                $knowledgeBaseCategory->id,
                $request->method(),
                $knowledgeBaseCategory->category_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update KB category]
     * @param Int     $id      [Row id]
     *
     * @return boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $knowledgeBaseCategory = KnowledgeBaseCategory::find($id);

        // --
        // Upload if new attachment
        if ($input["file"] && $input["file"] != $knowledgeBaseCategory->category_logo) {
            $files = public_path() .'/uploads/category_attachment/'.
            $knowledgeBaseCategory->category_logo;
            if (file_exists($files) && $knowledgeBaseCategory->category_logo) {
                unlink($files);
            }

            $input['category_logo'] = $this->uploadHelper->uploadAttachment(
                $input,
                'category_attachment'
            );
        } else {
            $input['category_logo'] = '';
        }
        
        $knowledgeBaseCategory->fill($input);

        if ($knowledgeBaseCategory->save()) {
            // --
            // Add activities
            createUserActivity(
                KnowledgeBaseCategory::MODULE_NAME,
                $knowledgeBaseCategory->id,
                $request->method(),
                $knowledgeBaseCategory->category_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete KB category]
     * @param Int     $id      [Row id]
     *
     * @return boolean
     */
    public function delete($request, $id)
    {
        $knowledgeBaseCategory = KnowledgeBaseCategory::findOrFail($id);

        //Remove article related to category
        $knowledgeBaseArticle = $knowledgeBaseCategory->articles()->get();

        if ($knowledgeBaseArticle) {
            foreach ($knowledgeBaseArticle as $article) :
                $files = public_path() .'/uploads/article_attachment/'.
                $article->file_name;
                if (file_exists($files) && $article->file_name) {
                    unlink($files);
                }
                $deleteArticle = KnowledgeBaseArticle::findOrFail($article->id);
                $deleteArticle->delete();
            endforeach;
        }
        
        // --
        // Remove file attached to category.
        $files = public_path() .'/uploads/category_attachment/'.
        $knowledgeBaseCategory->category_logo;
        if (file_exists($files) && $knowledgeBaseCategory->category_logo) {
            unlink($files);
        }

        //Remove category
        if ($knowledgeBaseCategory->delete()) {
            // --
            // Add activities
            createUserActivity(
                KnowledgeBaseCategory::MODULE_NAME,
                $knowledgeBaseCategory->id,
                $request->method(),
                $knowledgeBaseCategory->category_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Search category and article.
     *
     * @param String $search [Search artical string]
     *
     * @return Object
     */
    public function searchCategoryArticle($search)
    {
        return KnowledgeBaseCategory::with('articles')
            ->whereHas(
                'articles',
                function ($q) use ($search) {
                    $q->where('article_title', 'LIKE', "%{$search}%");
                }
            )
            ->orWhere('category_name', 'LIKE', "%{$search}%")
            ->get();
    }
}

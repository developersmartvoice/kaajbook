<?php

namespace Modules\KnowledgeBaseArticle\Repositories;

use Modules\KnowledgeBaseArticle\Models\KnowledgeBaseArticle;
use Modules\KnowledgeBaseCategory\Models\KnowledgeBaseCategory;
use Modules\Helper\Helpers\UploadFileHelper;

use Auth;

/**
 * Class KnowledgeBaseArticleRepository
 *
 * Knowledge Base Article create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\KnowledgeBaseArticle
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class KnowledgeBaseArticleRepository
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
     * @return Object
     */
    public function findAll()
    {
        return KnowledgeBaseArticle::all();
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return KnowledgeBaseArticle::find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create knowledgebase artical]
     *
     * @return boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $user = Auth::user()->id;
        $knowledgeBaseArticle = new KnowledgeBaseArticle;

        // --
        // Upload attachment
        if (isset($input["file"]) && $input["file"]) {
            $input['file_name'] = $this->uploadHelper->uploadAttachment(
                $input,
                'article_attachment'
            );
        }

        $knowledgeBaseArticle->user_id = $user;
        $knowledgeBaseArticle->fill($input);

        if ($knowledgeBaseArticle->save()) {
            // --
            // Add activities
            createUserActivity(
                KnowledgeBaseArticle::MODULE_NAME,
                $knowledgeBaseArticle->id,
                $request->method(),
                $knowledgeBaseArticle->article_title,
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
     * @param Request $request [Request for update knowledgebase artical]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $knowledgeBaseArticle = KnowledgeBaseArticle::find($id);

        // --
        // Upload if new attachment
        if (isset($input["file"]) && $input["file"]) {
            $files = public_path() .'/uploads/article_attachment/'.
            $knowledgeBaseArticle->file_name;
            if ($knowledgeBaseArticle->file_name && $input["file"] != $input["file_name"]) {
                if (file_exists($files) && $files) {
                    unlink($files);
                }
            }

            if ($input["file"] != $input["file_name"]) {
                $input['file_name'] = $this->uploadHelper->uploadAttachment(
                    $input,
                    'article_attachment'
                );
            }
        } else {
            $input['file_name'] = '';
        }

        $knowledgeBaseArticle->fill($input);
        if ($knowledgeBaseArticle->save()) {
            // --
            // Add activities
            createUserActivity(
                KnowledgeBaseArticle::MODULE_NAME,
                $knowledgeBaseArticle->id,
                $request->method(),
                $knowledgeBaseArticle->article_title,
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
     * @param Request $request [Request for delete knowledgebase artical]
     * @param Int     $id      [Row id]
     *
     * @return boolean
     */
    public function delete($request, $id)
    {
        $knowledgeBaseArticle = KnowledgeBaseArticle::findOrFail($id);

        if ($knowledgeBaseArticle->delete()) {
            // --
            // Add activities
            createUserActivity(
                KnowledgeBaseArticle::MODULE_NAME,
                $knowledgeBaseArticle->id,
                $request->method(),
                $knowledgeBaseArticle->article_title,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }
}

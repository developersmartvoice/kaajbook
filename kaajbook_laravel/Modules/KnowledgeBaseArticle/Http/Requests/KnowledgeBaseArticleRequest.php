<?php

namespace Modules\KnowledgeBaseArticle\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class KnowledgeBaseArticleRequest
 *
 * The Validation Rules is Defined for Knowledgebase Articles.
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
class KnowledgeBaseArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for dynamic validation]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'description' => 'required',
        ];
        if ($request->has('file') && !empty($request->input('file'))) {
            $rules['file_extension'] = 'required';
        }
        switch ($this->method()) {

            case 'POST':
            {
                $rules['article_title'] = [
                    'required', 
                    'max:'.config('core.max_length'),
                    Rule::unique(config('core.acl.knowledge_base_article_table'))->where(
                        function ($query) {
                            return $query
                                ->where('category_id', $this->request->get('category_id'))
                                ->where('deleted_at', null);
                        }
                    )
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                $rules['article_title'] = [
                    'required', 
                    'max:'.config('core.max_length'),
                    Rule::unique(config('core.acl.knowledge_base_article_table'))->where(
                        function ($query) {
                            return $query->whereNotIn('id', [$this->request->get('id')])
                                ->where('category_id', $this->request->get('category_id'))
                                ->where('deleted_at', null);
                        }
                    )
                ];
                return $rules;
            }
            default:
                return [];
        }
        
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

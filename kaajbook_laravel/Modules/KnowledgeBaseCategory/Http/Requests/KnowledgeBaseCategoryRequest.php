<?php

namespace Modules\KnowledgeBaseCategory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class KnowledgeBaseCategoryRequest
 *
 * The Validation Request is Defined for Knowledgebase Categories.
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
class KnowledgeBaseCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
        case 'POST':
        {
            return [
                'category_name' => 'required|max:50|unique:' .config('core.acl.knowledge_base_category_table').',category_name,NULL,id,deleted_at,NULL',
                'file' => 'required',
                'file_extension' => 'required'
                ];
        }
        case 'PUT':
        case 'PATCH':
        {
            $id = $this->segment(3);
            return [
                'category_name' => 'required|max:50|unique:' .config('core.acl.knowledge_base_category_table').',category_name,'.$id.',id,deleted_at,NULL',
                ];
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.required' => __('validation.custom.messages.category_logo.required')
        ];
    }
}

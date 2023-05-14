<?php

namespace Modules\CustomField\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class CreateCustomFieldRequest
 *
 * The Request is Defined for Create CustomField.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\CustomField
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateCustomFieldRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for get form id] 
     * 
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'form_id' => 'required',
            'field_label'  => [
                'required', 
                Rule::unique(config('core.acl.custom_fields'))->where(
                    function ($query) use ($request) {
                        return $query
                            ->where('form_id', $request->get('form_id'))
                            ->where('deleted_at', null);
                    }
                ),
            ],
            'field_type' => 'required'
        ];
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

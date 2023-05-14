<?php

namespace Modules\CustomField\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class UpdateCustomFieldRequest
 *
 * The Request is Defined for Update CustomField.
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
class UpdateCustomFieldRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'form_id' => 'required',
            'field_label'  => [
                'required',
                Rule::unique(config('core.acl.custom_fields'))->where(
                    function ($query) {
                        return $query
                            ->where('form_id', $this->request->get('form_id'))
                            ->whereNotIn('id', [$this->segment(3)])
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

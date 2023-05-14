<?php

namespace Modules\LeaveType\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LeaveTypeRequest
 *
 * The Validation Rules is Defined for Leave type.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\LeaveType
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class LeaveTypeRequest extends FormRequest
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
            return [
                        'leave_type' => 'required|unique:'.config('core.acl.leave_types_table').',leave_type,NULL,id,deleted_at,NULL',
                        'no_of_leaves' => 'required|numeric',
                        'color' => 'required'
                    ];

        case 'PUT':
        case 'PATCH':
            $id = $this->request->get('id');
            return [
                        'leave_type' => 'required|unique:'.config('core.acl.leave_types_table').',leave_type,'.$id.',id,deleted_at,NULL',
                        'no_of_leaves' => 'required|numeric',
                        'color' => 'required'
                    ];
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

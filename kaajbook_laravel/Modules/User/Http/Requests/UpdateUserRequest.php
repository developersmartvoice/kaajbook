<?php

namespace Modules\User\Http\Requests;

use App\Rules\PhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
/**
 * Class UpdateUserRequest
 *
 * The Validation Rules is Defined for Update User.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @param Request $request [Params for type]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $id = $this->segment(3);
        $rules = [
            'user_generated_id' => 'required|unique:'.config('core.acl.users_table').',user_generated_id,'.$id.',id,deleted_at,NULL',
            'username' => 'required|min:3|max:30|regex:/^\S*$/|unique:'.config('core.acl.users_table').',username,'.$id.',id,deleted_at,NULL',
            'emp_id' => 'required|unique:'.config('core.acl.users_table').',emp_id,'.$id.',id,deleted_at,NULL',
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:20',
            'email' => 'required|email|unique:'.config('core.acl.users_table').',email,'.$id.',id,deleted_at,NULL',
            'password' => [
                'nullable',
                'min:3',
                'max:30',
                'confirmed'
            ],
            'mobile' => ['nullable',new PhoneValidationRule],
            'phone' => ['nullable',new PhoneValidationRule],
            'department_roles' => 'required'
        ];
        if ($request->has('type') && $request->get('type') == 'list') {
            unset($rules['department_roles']);
        }
        if ($request->has('is_client') && $request->get('is_client') == 1) {
            unset($rules['emp_id']);
        }
        return $rules;
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

<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneValidationRule;

/**
 * Class CreateUserRequest
 *
 * The Validation Rules is Defined for Create User.
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
class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_generated_id' => 'required|unique:'.
            config('core.acl.users_table'),
            'username' => 'required|min:3|max:30|regex:/^\S*$/|unique:'.config('core.acl.users_table').',username,NULL,id,deleted_at,NULL',
            'emp_id' => 'required|unique:'. config('core.acl.users_table').',emp_id,NULL,id,deleted_at,NULL',
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:20',
            'email' => 'required|email|unique:'.config('core.acl.users_table').',email,NULL,id,deleted_at,NULL',
            'password' => [
                'required',
                'min:3',
                'max:30',
                'confirmed'
            ],
            'mobile' => ['nullable',new PhoneValidationRule],
            'phone' => ['nullable',new PhoneValidationRule],
            'department_roles' => 'required',
            'assign_permission' => 'required'
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

<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneValidationRule;

/**
 * Class CreateClientRequest
 *
 * The request is Defined for Client.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Client
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'emp_id' => 'required|unique:'. config('core.acl.users_table').',emp_id,NULL,id,deleted_at,NULL',
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:20',
            'username' => 'required|min:3|max:30|regex:/^\S*$/|unique:'.config('core.acl.users_table').',username,NULL,id,deleted_at,NULL',
            'email' => 'required|email|unique:'.config('core.acl.users_table').',email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:3|max:30|confirmed',
            'user_generated_id' => 'required|unique:'.config('core.acl.users_table'),
            'mobile' => ['nullable',new PhoneValidationRule],
            'phone' => ['nullable',new PhoneValidationRule],
            'department_roles' => 'required',
            'clients.company_email' => 'nullable|email',
            'clients.company_mobile' => ['nullable',new PhoneValidationRule],
            'clients.company_phone' => ['nullable',new PhoneValidationRule],
            'clients.website' => 'nullable|url',
            'clients.company_zipcode' => 'nullable|integer',
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

<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Http\Request;
use App\Rules\PhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateClientRequest
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
class UpdateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for get clients]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $id = $this->segment(3);
        $rules =  [
            // 'emp_id' => 'required|unique:'.config('core.acl.users_table').',emp_id,'.$id.',id,deleted_at,NULL',
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:20',
            'username' => 'required|min:3|max:30|regex:/^\S*$/|unique:'.config('core.acl.users_table').',username,'.$id.',id,deleted_at,NULL',
            'email' => 'required|email|unique:'.config('core.acl.users_table').',email,'.$id.',id,deleted_at,NULL',
            'mobile' => ['nullable',new PhoneValidationRule],
            'phone' => ['nullable',new PhoneValidationRule],
            'department_roles' => 'required',
            'clients.company_email' => 'nullable|email',
            'clients.company_mobile' => ['nullable',new PhoneValidationRule],
            'clients.company_phone' => ['nullable',new PhoneValidationRule],
            'clients.website' => 'nullable|active_url',
            'clients.company_zipcode' => 'nullable|integer',
        ];

        if ($request->get("type") == "list") {
            unset($rules['department_roles']);
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

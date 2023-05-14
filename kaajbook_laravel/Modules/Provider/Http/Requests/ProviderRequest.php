<?php

namespace Modules\Provider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

/**
 * Class ProviderRequest
 *
 * The request is Defined for create Provider.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Provider
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProviderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($this->method()) {
        case 'POST':
            return [
                'firstname'  => [
                    'required', 
                    'max:50', 
                    Rule::unique(config('core.acl.providers_table'))->where(
                        function ($query) use ($request) {
                            return $query
                                ->where('firstname', $request->get('firstname'))
                                ->where('lastname', $request->get('lastname'))
                                ->where('deleted_at', null);
                        }
                    ),
                ],
                'lastname' => 'required|max:50',
                'email' => 'required|email|unique:'.config('core.acl.providers_table').',email,NULL,id,deleted_at,NULL',
                'color' => 'required|max:10',
            ];
        case 'PUT':
        case 'PATCH':
            $id = $this->segment(3);
            return [
                'firstname'  => [
                    'required', 
                    'max:50', 
                    Rule::unique(config('core.acl.providers_table'))->where(
                        function ($query) use ($request) {
                            return $query
                                ->where('firstname', $request->get('firstname'))
                                ->where('lastname', $request->get('lastname'))
                                ->whereNotIn('id', [$request->get('id')])
                                ->where('deleted_at', null);
                        }
                    ),
                ],
                'lastname' => 'required|max:50',
                'email' => 'required|email|unique:'.config('core.acl.providers_table').',email,'.$id.',id,deleted_at,NULL',
                'color' => 'required|max:10',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.unique' => __('validation.custom.messages.firstname.unique')
        ];
    }
}

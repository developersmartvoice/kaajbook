<?php

namespace Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use App\Rules\PhoneValidationRule;

/**
 * Class CreateUpdateSettingRequest
 *
 * The Validation Rules is Defined for Setting.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Setting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateUpdateSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $form_for = $this->request->get('form_for');
        switch ($form_for) {
        case 'company_detail':
            return [
                'company_name' => 'required|max:50',
                'company_legal_name' => 'nullable|max:50',
                'company_short_name' => 'required|max:50',
                'company_phone' => ['nullable',new PhoneValidationRule],
                'company_email' => 'nullable|email',
                'company_website' => 'nullable|url',
                'company_address' => 'required'
            ];
            break;
        case 'email_setting':
            return [
                'company_from_email' => 'nullable|email',
                'smtp_protocol' => 'required',
                'smtp_host' => 'required',
                'smtp_user' => 'required',
                'smtp_port' => 'required'
            ];
            break;
        case 'system_setting':
            return [
                'default_language' => 'required',
                'tables_pagination_limit' => 'required|integer',
                'date_format' => 'required',
                'time_format' => 'required',
            ];
            break;
        case 'system_update':
            return [
                'update_url' => 'nullable|url'
            ];
            break;
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

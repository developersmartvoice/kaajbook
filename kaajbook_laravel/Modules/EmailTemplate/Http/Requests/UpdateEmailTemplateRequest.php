<?php

namespace Modules\EmailTemplate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateEmailTemplateRequest
 *
 * The Validation Rules is Defined for Email Template.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailTemplate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UpdateEmailTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->segment(3);
        return [
            'email_group_id' => 'required',
            'template_name' => 'required|unique:'.config('core.acl.email_template_table').',template_name,'.$id.',id,deleted_at,NULL',
            'template_subject' => 'required',
            'template_body' => 'required',
            'type' => 'required'
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

<?php

namespace Modules\Mailbox\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class ComposeMailRequest
 *
 * The Validation Rules is Defined for ComposeMail.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\ComposeMail
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ComposeMailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to' => 'required',
            'subject' => 'required'
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

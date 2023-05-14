<?php

namespace Modules\DefectAttachment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DefectAttachmentRequest
 *
 * The Validation Rules is Defined for Defect Attachment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Defect
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'defect_id' => 'required',
            'file_name' => 'required',
            'file' => 'required',
            'file_extension' => 'required'
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

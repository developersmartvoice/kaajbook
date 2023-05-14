<?php

namespace Modules\IncidentAttachment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IncidentAttachmentRequest
 *
 * The Validation Rules is Defined for Incident Attachment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\IncidentAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'incident_id' => 'required',
            'file_name' => 'required|max:'.config('core.max_length'),
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

<?php

namespace Modules\TaskAttachment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateTaskAttachmentRequest
 *
 * The Validation Rules is Defined for Task Attachment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\TaskAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateTaskAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task_id' => 'required',
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

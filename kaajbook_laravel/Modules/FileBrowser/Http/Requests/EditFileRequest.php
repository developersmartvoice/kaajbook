<?php

namespace Modules\FileBrowser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EditFileRequest
 *
 * The Validation Rules is Defined for Edit File.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\File
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EditFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file_name' => 'required|max:'.config('core.max_length'),
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

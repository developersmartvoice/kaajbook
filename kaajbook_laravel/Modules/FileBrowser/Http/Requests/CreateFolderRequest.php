<?php

namespace Modules\FileBrowser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateFolderRequest
 *
 * The Validation Rules is Defined for Create Folder.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Folder
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateFolderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folder_name'  => [
                'required', 
                Rule::unique(config('core.acl.folder_table'))->where(
                    function ($query) {
                        return $query
                            ->where('user_id', auth()->user()->id)
                            ->where('parent_folder', $this->request->get('parent_folder'));
                    }
                ),
            ],
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

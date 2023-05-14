<?php

namespace Modules\FileBrowser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class EditFolderRequest
 *
 * The Validation Rules is Defined for Folder.
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
class EditFolderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->get('id');
        return [
            'folder_name'  => [
                'required',
                Rule::unique(config('core.acl.folder_table'))->where(
                    function ($query) {
                        return $query->whereNotIn('id', [$this->request->get('id')])
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

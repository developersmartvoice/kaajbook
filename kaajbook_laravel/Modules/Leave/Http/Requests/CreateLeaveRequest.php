<?php

namespace Modules\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class CreateLeaveRequest
 *
 * The Validation Rules is Defined for create leave.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\Leave
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreateLeaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for validate leave]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'duration' => 'required',
            'duration_type' => 'required_if:duration,half',
            'leave_date' => [
                'required_if:duration,single,half',
                'date',
                'nullable',
                Rule::unique(config('core.acl.leaves_table'))->where(
                    function ($query) use ($request) {
                        return $query->where('user_id', $request->get('user_id'))
                            ->where('leave_date', $request->get('leave_date'))
                            ->where('deleted_at', null);
                    }
                )
            ],
            'multi_date' => [
                'required_if:duration,multiple'
            ],
            'reason' => 'required'
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

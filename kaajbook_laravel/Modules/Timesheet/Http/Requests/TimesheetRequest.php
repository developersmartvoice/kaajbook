<?php

namespace Modules\Timesheet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class TimesheetRequest
 *
 * The Validation Rules is Defined for Create Timesheet.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TimesheetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for validate request]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($this->method()) {
        case 'POST':
            $rules = [
            'module_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            ];

            if (in_array($request->get('module_id'), [1,2,3,4,5])) {
                $rules["module_related_id"] = 'required';
            }
            
            return $rules;
        case 'PUT':
        case 'PATCH':
            $rules = [
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            ];

            if (in_array($request->get('module_id'), [1,2,3,4,5])) {
                $rules["module_related_id"] = 'required';
            }
            
            return $rules;
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

<?php

namespace Modules\Timesheet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TimerLogRequest
 *
 * The Validation Rules is Defined for Create Timer Log.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.1@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.1
 */
class TimerLogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reference_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d H:i:s'
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

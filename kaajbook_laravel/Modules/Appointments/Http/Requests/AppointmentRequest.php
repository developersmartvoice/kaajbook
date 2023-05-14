<?php

namespace Modules\Appointments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class AppointmentRequest
 *
 * Appointment create and and update validation rules.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Appointment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AppointmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for appointment]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|max:100',
                    'provider_id' => 'required',
                    'attendees' => 'required|max:100',
                    'start_date_time' => 'required',
                    'end_date_time' => 'required',
                ];
            case 'PUT':
            case 'PATCH':
                $rules = [
                    'title' => 'required|max:100',
                    'provider_id' => 'required',
                    'attendees' => 'required|max:100',
                    'start_date_time' => 'required',
                    'end_date_time' => 'required',
                ];
                if ($request->get("recurrence")) {
                    $rules['recurrence_occurrences'] = 'required';
                    $rules['recurrence_pattern'] = 'required';
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

<?php

namespace Modules\Incident\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IncidentRequest
 *
 * The Validation Rules is Defined for Incident.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Incident
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
        case 'POST':
            return [
                    'incident_name' => 'required|max:'.config('core.max_length'),
                    'incident_type' => 'required',
                    'priority' => 'required',
                    'status' => 'required',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date|after_or_equal:start_date'
                ];

        case 'PUT':
        case 'PATCH':
            $id = $this->segment(3);
            return [
                    'incident_name' => 'required|max:'.config('core.max_length'),
                    'incident_type' => 'required',
                    'priority' => 'required',
                    'status' => 'required',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date|after_or_equal:start_date'
                ];
            
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

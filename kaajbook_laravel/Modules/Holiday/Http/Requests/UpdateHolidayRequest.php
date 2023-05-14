<?php

namespace Modules\Holiday\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateHolidayRequest
 *
 * The Validation Rules is Defined for Update Holiday.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Holiday
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UpdateHolidayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_name'  => [
                'required',
                'max:50',
                Rule::unique(config('core.acl.holiday_table'))->where(
                    function ($query) {
                        return $query->whereNotIn('id', [$this->request->get('id')])
                            ->where('date', date("Y-m-d",strtotime($this->request->get('date'))))
                            ->where('deleted_at', null);
                    }
                ),
            ],
            'date' => 'required|date'
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

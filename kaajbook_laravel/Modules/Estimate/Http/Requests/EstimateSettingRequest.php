<?php

namespace Modules\Estimate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EstimateSettingRequest
 *
 * The Validation Rules is Defined for Estimate Setting.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Estimate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class EstimateSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'estimate_prefix' => 'required|max:10',
            'due_after' => 'required|integer',
            'auto_remind_before' => 'required_if:auto_remind,1'
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

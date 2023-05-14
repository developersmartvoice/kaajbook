<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Modules\Invoice\Models\Invoice;

/**
 * Class CreatePaymentRequest
 *
 * The Validation Rules is Defined for Create Payment.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Payment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreatePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request [Request for create payment]
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'invoice_id' => 'required',
            'payment_method' => 'required',
            'amount' => ['required', 'numeric', 'gt:0']
        ];
        if ($this->request->has('invoice_id')) {
            $invoice = Invoice::select('total_due_amount')->findOrFail($request->get('invoice_id'));
            $rules['amount'] = ['required', 'numeric', 'gt:0', 'lte:'.$invoice->total_due_amount];
        }
        return $rules;
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

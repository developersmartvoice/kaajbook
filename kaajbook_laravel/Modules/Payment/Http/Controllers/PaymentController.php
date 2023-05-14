<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Payment\Http\Requests\CreatePaymentRequest;
use Modules\Payment\Http\Requests\UpdatePaymentRequest;
use Modules\Payment\Repositories\PaymentRepository;
use Modules\Payment\Models\PaymentGatewaySetting;
use Modules\Invoice\Models\Invoice;
use Modules\User\Models\User\User;

/**
 * Class PaymentController
 *
 * Payment create, update, delete and view.
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
class PaymentController extends Controller
{
	protected $paymentRepo;

	/**
	 * Instantiate a new repository instance.
	 *
	 * @param PaymentRepository $paymentRepo [Object]
	 *
	 * @return void
	 */
	public function __construct(PaymentRepository $paymentRepo)
	{
		$this->paymentRepo = $paymentRepo;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request [Request for get leaves]
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request [Request for get payments]
	 *
	 * @return Response
	 */
	public function getAllPayments(Request $request)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(114, 'view')) {
			return response()->json("Access denied", 403);
		}

		return $this->paymentRepo->getAllPayments($request);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request [Request for create payment]
	 *
	 * @return Response
	 */
	public function store(CreatePaymentRequest $request)
	{
		if (!$request->has('isPaypal_payment') && !$request->get('isPaypal_payment')) {
			// --
			// Check role/permission
			if (!AdminHelper::can_action(114, 'created')) {
				return response()->json("Access denied", 403);
			}
        }

		$payment = $this->paymentRepo->create($request);

		if ($payment) {
			return response()->json('success');
		} else {
			return response()->json('error', 401);
		}
	}

	/**
	 * Show the specified resource.
	 *
	 * @param Int $id [Row id]
	 *
	 * @return Response
	 */
	public function show($id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(114, 'view')) {
			return response()->json("Access denied", 403);
		}

		$payment = $this->paymentRepo->findById($id);
		if ($payment) {
			return response()->json($payment);
		}
		return response()->json('error', 401);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request [Request for update payment]
	 * @param Int     $id      [Row id]
	 *
	 * @return Response
	 */
	public function update(UpdatePaymentRequest $request, $id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(114, 'edited')) {
			return response()->json("Access denied", 403);
		}

		$payment = $this->paymentRepo->update($request, $id);

		if ($payment) {
			return response()->json('success');
		} else {
			return response()->json('error', 401);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request [Request for destroy payment]
	 * @param int     $id      [Row id]
	 *
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(114, 'deleted')) {
			return response()->json("Access denied", 403);
		}

		if ($this->paymentRepo->delete($request, $id)) {
			return response()->json('success');
		} else {
			return response()->json('error', 401);
		}
	}

	/**
	 * Download payment receipt.
	 *
	 * @param Int $id [Row id]
	 * @param Int $clientId [User/Client id]
	 *
	 * @return Response
	 */
	public function download($id, $clientId)
	{
		$id = decrypt($id);
		// --
		// Check role/permission
		if (!$this->paymentRepo->checkPermission($id, $clientId)) {
			return redirect('/');
		}
		
		return $this->paymentRepo->download($id);
	}

	 /**
	 * Stripe checkout charge from customer.
	 *
	 * @param Request $request [Request for charge payment]
	 *
	 * @return Response
	 */
	public function stripePaymentCharge(CreatePaymentRequest $request)
	{
		$response = [];

		try {
			$user = User::with('clients')
		            ->where('id', $request['client_id'])
		            ->first();

			$invoice = Invoice::find($request['invoice_id']);
			$settings = PaymentGatewaySetting::first();
			\Stripe\Stripe::setApiKey($settings->stripe_secret_key);

			// --
			// Create customer
			$customer = \Stripe\Customer::create(array(
				"email"  => $user->email,
				'name' => $user->firstname . ' '. $user->lastname,
				'address' => [
					'line1' => $user->address,
					'postal_code' => $user->postal_code,
					'city' => $user->city,
					'state' => $user->state,
					'country' => $user->country
				],
				"source" => $request['description']
			));

			if($customer && $customer->id ) {
				$response = \Stripe\Charge::create(array(
					"currency" => strtolower($request['currency']),
					"customer" => $customer->id,
					'description' => 'Payment for invoice #'.$invoice->invoice_number,
					"receipt_email" => $request['email'],
					"amount"   => $request['amount'] // amount in cents
				));
			}

		} catch(\Stripe\Exception\CardException $e) {
			$error = $e->getMessage();
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			$error = $e->getMessage();
		} catch (\Stripe\Exception\AuthenticationException $e) {
			$error = $e->getMessage();
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			$error = $e->getMessage();
		} catch (\Stripe\Exception\Exception $e) {
			$error = $e->getMessage();
		} catch (\Exception $e) {
			$error = $e->getMessage();
		}

		if (!empty($response) && $response->status == 'succeeded') {
			$this->paymentRepo->stripePaymentCharge($request, [
				'invoice_id' => $invoice->id,
				'client_id' => $user->id,
				'transaction_id' => $response->id,
				'payment_method' => 'Stripe Checkout',
				'amount' => $response->amount,
				'status' => 'successful'
			], true);
			
			return response()->json('success');
		} else {
			$this->paymentRepo->stripePaymentCharge($request, [
				'invoice_id' => $invoice->id,
				'client_id' => $user->id,
				'payment_method' => 'Stripe Checkout',
				'amount' => $request['amount'],
				'status' => 'failed'
			], true);

			return response()->json($error, 500);
		}
	}
}

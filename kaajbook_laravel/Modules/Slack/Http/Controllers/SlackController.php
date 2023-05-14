<?php

namespace Modules\Slack\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Slack\Models\Slack;
use Modules\Setting\Models\Setting;
use Modules\Slack\Models\SlackSetting;

/**
 * Trait SlackController
 *
 * The Controller is Defined For Slack.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Slack
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class SlackController extends Controller
{
	/**
	 * Get slack authentication code.
	 *
	 * @param Request $request [Request for get slack code]
	 *
	 * @return URL
	 */
	public function getSlackCode(Request $request)
	{
		if (null !== $request->get('code')) {
			$userId = $request->get('state');
			$settings = SlackSetting::select(['slack_client_id', 'slack_client_secret', 'slack_redirect_URL'])->first();
		
			// --
			// Get token
			$curl = curl_init();
			curl_setopt_array(
				$curl, array(
				CURLOPT_URL => 'https://slack.com/api/oauth.v2.access?client_id='.$settings->slack_client_id.'&'.'client_secret='.$settings->slack_client_secret.'&'.'code='.$request->get('code').'&'.'redirect_uri='.$settings->slack_redirect_URL,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30000,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_HTTPHEADER => array(
					"content-type: application/x-www-form-urlencoded",
				),
				)
			);

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$url = config('app.front_url').'?error='. $err;
			} else {
				$res = json_decode($response);

				if (!empty($res->error)) {
					$url = config('app.front_url').'?error='. $res->error;
				}

				// --
				// Save in database
				$inputs = array('user_id' => $userId, 'access_token' => $res->access_token, 'team_id' => $res->team->id, 'channel_id' => $res->incoming_webhook->channel_id);

				$slack = Slack::where('user_id', $userId)->first();

				if (!empty($slack)) {
					$slack->access_token = $res->access_token;
					$slack->team_id = $res->team->id;
					$slack->channel_id = $res->incoming_webhook->channel_id;
					$slack->save();
				} else {
					$slack = new Slack;
					$slack->fill($inputs)->save();
				}

				// --
				// Update slack settings
				$slackSetting = SlackSetting::first()->fill(['is_authenticate' => true])->save();

				$url = config('app.front_url').'?success=Integration created successfully.';
			}
		} elseif (null !== $request->get('error')) {
			$url = config('app.front_url').'?error='. $request->get('error');
		}

		return redirect($url);
	}

	/**
	 * Get test slack command
	 *
	 * @param Request $request [Request for get slack command]
	 *
	 * @return Response
	 */
	public function getSlackCommand1(Request $request)
	{
		return \Response::json([
			"response_type" => "in_channel",
			"text" => "Thanks for your request, we'll process it and get back to you."
		]);
	}
}
